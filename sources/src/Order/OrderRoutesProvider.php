<?php

namespace HsBremen\WebApi\Order;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Swagger\Annotations as SWG;

class OrderRoutesProvider implements ControllerProviderInterface
{
    /** {@inheritdoc} */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];
        

        /**
         * @SWG\Get(
         *     path="/order/",
         *     tags={"order"},
         *     @SWG\Response(response="200", description="An example resource")
         * )
         */
        // see https://github.com/silexphp/Silex/issues/149
        $controllers->get('/', 'service.order:getList');
        /**
         * @SWG\Get(
         *     path="/order/{id}",
         *     tags={"order"},
         *     @SWG\Response(
         *         response="200",
         *         description="An example resource",
         *          @SWG\Schema(ref="#/definitions/order")
         *     )
         * )
         */
        $controllers->get('/{orderId}', 'service.order:getDetails');
        /**
         * @SWG\Post(
         *     tags={"order"},
         *     path="/order/",
         *     @SWG\Parameter(name="order", in="body", @SWG\Schema(ref="#/definitions/order")),
         *     @SWG\Response(response="201", description="An example resource")
         * )
         */
        $controllers->post('/', 'service.order:createOrder');
        /**
         * @SWG\Put(
         *     tags={"order"},
         *     path="/order/{id}",
         *     @SWG\Response(
         *          response="200",
         *          description="An example resource",
         *          @SWG\Schema(ref="#/definitions/order")
         *     )
         * )
         */
        $controllers->put('/{orderId}', 'service.order:changeOrder');

        return $controllers;
    }
}
