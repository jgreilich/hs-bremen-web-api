<?php


use HsBremen\WebApi\Application;
use HsBremen\WebApi\Security\UserProvider;
use HsBremen\WebApi\Security\UserService;
use HsBremen\WebApi\Security\UserServiceProvider;

class UserServiceProviderTest extends PHPUnit_Framework_TestCase
{

    
    /**
     * @test
     */
    public function registerServiceIsRegistered()
    {
        $userRepo = self::getMockBuilder(UserProvider::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $connection = self::getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $provider = new UserServiceProvider();
        $app      = new Application();

        $app['repo.user'] = $userRepo;
        $app['db'] = $connection;
        
        $provider->register($app);
        
        self::assertArrayHasKey('service.register',$app);
        self::assertInstanceOf(UserService::class, $app['service.register']);
    }
    
    
}
