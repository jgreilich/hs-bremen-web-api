<?php

namespace HsBremen\WebApi\Security;

use Silex\Application;
use Silex\Provider\SecurityServiceProvider;
use Silex\ServiceProviderInterface;
use HsBremen\WebApi\Security\UserProvider;

class SecurityProvider implements ServiceProviderInterface
{

    /** {@inheritdoc} */
    public function register(Application $app)
    {
        $app->register(new SecurityServiceProvider());

        $app['security.firewalls'] = [
//            'register' => [
//                'pattern' => '^/register',
//                'http' => true
//            ],
//            'user' => [
//                'pattern' => '^/',
//                'http'    => true,
//                'users'   => $app->share(function() use ($app) {
//                    return new UserProvider($app);
//                }),
        'admin' => [
            'pattern' => '^/order',
            'http' => true,
            'users' => [
                // raw password is foo
                'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
            ],
]
        ];
    }

    /** {@inheritdoc} */
    public function boot(Application $app)
    {
    }
}
