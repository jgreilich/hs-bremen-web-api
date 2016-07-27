<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 26.07.2016
 * Time: 23:39
 */

namespace HsBremen\WebApi\Security;


use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class UserRoutesProvider implements ControllerProviderInterface
{


    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];
        
        /**
         * @SWG\POST(
         *     path="/register/",
         *     tags="register",
         *     @SWG\Response(response="201", description="User is registered."
         */
        $controllers->post('/', 'service.register:registerUser');


        return $controllers;
    }
}