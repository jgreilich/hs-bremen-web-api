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
            'register' => [
                'pattern' => '^/register',
            ],
            'user' => [
                'pattern' => '^/',
                'http'    => true,
                'users'   => $app->share(function() use ($app) {
                    return $app['repo.user'];
                }),
            ],
        ];
    }

    /** {@inheritdoc} */
    public function boot(Application $app)
    {
    }
}
