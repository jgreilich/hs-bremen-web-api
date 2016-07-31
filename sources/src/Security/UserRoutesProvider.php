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

use Swagger\Annotations as SWG;

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
         * @SWG\Tag(name="register", description="Register new users")
         * @SWG\Definition(
         *     definition="credentials",
         *     type="object",
         *     properties={
         *     @SWG\Property(property="username", type="string"),
         *     @SWG\Property(property="password", type="string", format="password")
         *     }
         * )
         *
         *
         */

        /**
         * @SWG\Post(
         *     path="/register/",
         *     tags={"register"},
         *     @SWG\Parameter(name="credentials", in="body", @SWG\Schema(ref="#/definitions/credentials")),
         *     @SWG\Response(response="201", description="User is registered."),
         *     @SWG\Response(response="400", description="Database-Error"),
         *     @SWG\Response(response="412", description="User-Data (Auth-Header) required")
         * )
         */
        $controllers->post('/', 'service.register:registerUser');

        return $controllers;
    }
}