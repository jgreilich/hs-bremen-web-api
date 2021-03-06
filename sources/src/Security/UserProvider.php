<?php
// Source: http://www.bubblecode.net/en/2012/08/28/mysql-authentication-in-silex-the-php-micro-framework/


namespace HsBremen\WebApi\Security;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\DBAL\Connection;


class UserProvider implements UserProviderInterface
{
    /** @var Connection $conn */
    private $conn;
    /** @var Application $app */
    private $app;

    public function __construct(Application $app)
    {
        $this->conn = $app['db'];
        $this->app = $app;
    }

    public function loadUserByUsername($username)
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM users WHERE username = ?', array(strtolower($username)));
        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return new User($user['username'], $user['password'], explode(',', $user['roles']), true, true, true, true);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
    
    // Functions that are not from interface

    public function createTable()
    {
        // Create User Database-Table
        $sql = <<<EOS
CREATE TABLE IF NOT EXISTS `users` (
`username` VARCHAR(100) NOT NULL DEFAULT '',
`password` VARCHAR(255) NOT NULL DEFAULT '',
 `roles` VARCHAR(255) NOT NULL DEFAULT '',
 PRIMARY KEY (`username`),
 UNIQUE KEY `unique_username` (`username`)) 
EOS;
        $this->conn->exec($sql);
    }


    /**
     * @param string $username
     * @param string $password
     */
    public function saveNewUser($username, $password)
    {
        $password = $this->app['security.encoder.digest']->encodePassword($password, '');
        
        $this->conn->insert('users',
            ['username'=>$username, 'password' => $password, 'roles' => 'ROLE_USER']);
        
    }
    
    
    
}