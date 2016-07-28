<?php
use HsBremen\WebApi\Application;
use HsBremen\WebApi\Security\UserRoutesProvider;
use Silex\ControllerCollection;

/**
 * Created by PhpStorm.
 * User: julian
 * Date: 27.07.2016
 * Time: 16:36
 */
class UserRoutesProviderTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * @test
     */
    public function shouldRegisterRoutes()
    {
        $provider = new UserRoutesProvider();

        /** @var ControllerCollection $controllerFactory */
        $controllerFactory = $this->prophesize(ControllerCollection::class);
        $controllerFactory->post('/', 'service.register:registerUser')->shouldBeCalled();
        
        $app                        = new Application();
        $app['controllers_factory'] = $controllerFactory->reveal();
        $provider->connect($app);
    }

}
