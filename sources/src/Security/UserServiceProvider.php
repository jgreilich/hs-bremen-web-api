<?php


namespace HsBremen\WebApi\Security;


use Silex\Application;
use Silex\ServiceProviderInterface;

class UserServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        
        $app['repo.user'] = $app->share(function (Application $app){
            return new UserProvider($app);
        });
        
        $app['service.register'] = $app->share(function (Application $app) {
            return new UserService($app['repo.user']);
        });
        
        $app->mount('/register', new UserRoutesProvider());
        
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        /** @var UserProvider $user_provider */
        $user_provider = $app['repo.user'];
        
        // Create User-Table if not exists
        $user_provider->createTable();
        
    }
}