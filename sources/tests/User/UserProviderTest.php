<?php


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Statement;
use HsBremen\WebApi\Application;
use HsBremen\WebApi\Security\UserProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderTest extends PHPUnit_Framework_TestCase
{
    /** @var Connection|\PHPUnit_Framework_MockObject_MockObject $db */
    private $db;

    /** @var  UserProvider */
    private $user_repo;

    public function setUp()
    {
        $app = new Application();
        $this->user_repo = new UserProvider($app);
    }
    
    
    /**
     * @test
     */
    public function shouldThrowException()
    {
        $this->setExpectedException(UsernameNotFoundException::class);
        $this->user_repo->loadUserByUsername("mustermann");
    }
    
    /**
     * @test
     */
    public function shouldReturnUser()
    {
        $app = new Application();

        $stmt = $this->getMockBuilder(Statement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $stmt
            ->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'username' => 'mustermann',
                'password' => 'sowieso',
                'roles' => 'ROLE_USER'
            ]);
        
        $conn = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $conn
            ->expects($this->once())
            ->method('executeQuery')
            ->willReturn($stmt);
        
        $app['db'] = $conn;
        
        $userRepo = new UserProvider($app);
        
        $user = $userRepo->loadUserByUsername("mustermann");
        
        $this->assertInstanceOf(User::class,$user);
        $this->assertEquals("mustermann",$user->getUsername());
    }
    
    
    /**
     * @test
     */
    public function shouldCreateTable()
    {
        $sql = <<<EOS
CREATE TABLE IF NOT EXISTS `users` (
`username` VARCHAR(100) NOT NULL DEFAULT '',
`password` VARCHAR(255) NOT NULL DEFAULT '',
 `roles` VARCHAR(255) NOT NULL DEFAULT '',
 PRIMARY KEY (`username`),
 UNIQUE KEY `unique_username` (`username`)) 
EOS;
        
        $app = new Application();

        $conn = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $conn
            ->expects($this->once())
            ->method('exec')
            ->with($sql);

        $app['db'] = $conn;

        $userRepo = new UserProvider($app);
        
        $userRepo->createTable();
        
    }
    
    
    
    /**
     * @test
     */
    public function shouldEncodePasswordAndSafeNewUser()
    {
        $app = new Application();

        $conn = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $conn
            ->expects($this->once())
            ->method('insert')
            ->with('users',[
                'username' => 'admin',
                'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
                'roles' => 'ROLE_USER'
            ]);

        $app['db'] = $conn;

        /** @var UserProvider $userRepo */
        $userRepo = new UserProvider($app);

        $userRepo->saveNewUser("admin", "foo");
    }
    

}
