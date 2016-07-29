<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 28.07.2016
 * Time: 20:24
 */

namespace HsBremen\WebApi\Appointment;


use Silex\Application;
use Silex\ServiceProviderInterface;

class AppointmentServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $app['repo.appmnt'] = $app->share(function(Application $app){
           return new AppointmentRepository($app['db']); 
        });
        
        $app['service.appmnt'] = $app->share(function(Application $app){
            return new AppointmentService($app['repo.appmnt']);
        });
        
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
        /** @var AppointmentRepository $repo */
        $repo = $app['repo.appmnt'];
        $repo->createTable();
    }
}