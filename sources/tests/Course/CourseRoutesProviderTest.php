<?php

use HsBremen\WebApi\Application;
use HsBremen\WebApi\Course\CourseRoutesProvider;
use Silex\ControllerCollection;

class CourseRoutesProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldRegisterIndexRoute()
    {
        $provider = new CourseRoutesProvider();

        /** @var ControllerCollection $controllerFactory */
        $controllerFactory = $this->prophesize(ControllerCollection::class);
        
        $controllerFactory->get('/', 'service.course:getList')
            ->shouldBeCalled();

        $controllerFactory->post('/', 'service.course:newCourse')
            ->shouldBeCalled();

        $controllerFactory->get('/{courseId}', 'service.course:getDetails')
            ->shouldBeCalled();
        
        $controllerFactory->put('/{courseId}', 'service.course:changeCourse')
            ->shouldBeCalled();

        $controllerFactory->delete('/{courseId}', 'service.course:deleteCourse')
            ->shouldBeCalled();

//        $controllerFactory->post('/{courseId}','service.appmnt:newAppointment')
//            ->shouldBeCalled();

//        $controllerFactory->get('/{courseId}/{appmntId}', 'service.appmnt:getDetails')
//            ->shouldBeCalled();
//
//        $controllerFactory->put('/{courseId}/{appmntId}', 'service.appmnt:changeAppmnt')
//            ->shouldBeCalled();
//
//        $controllerFactory->delete('/{courseId}/{appmntId}', 'service.appmnt:deleteAppmnt')
//            ->shouldBeCalled();
//

        $controllerFactory->get('/{courseId}/subscribe','service.course:getSubscribers')
            ->shouldBeCalled();
        
        $controllerFactory->post('/{courseId}/subscribe','service.course:subscribe')
            ->shouldBeCalled();

        $controllerFactory->delete('/{courseId}/subscribe','service.course:unsubscribe')
            ->shouldBeCalled();

        
        $app                        = new Application();
        $app['controllers_factory'] = $controllerFactory->reveal();
        $provider->connect($app);
        
        
    }
    
    
    
}
