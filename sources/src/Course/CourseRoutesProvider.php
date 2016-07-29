<?php

namespace HsBremen\WebApi\Course;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Swagger\Annotations as SWG;

class CourseRoutesProvider implements ControllerProviderInterface
{
    /** {@inheritdoc} */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        /**
         * @SWG\Parameter(name="courseId", type="integer", in="path")
         * @SWG\Tag(name="course", description="All about the courses")
         */


        /**
         * @SWG\Get(
         *     path="/course/",
         *     tags={"course"},
         *     @SWG\Response(response="200", description="List of Courses")
         * )
         */
        $controllers->get('/', 'service.course:getList');

        /**
         * @SWG\Post(
         *     path="/course/",
         *     tags={"course"},
         *     @SWG\Parameter(name="course", in="body", @SWG\Schema(ref="#/definitions/course")),
         *     @SWG\Response(
         *         response="201",
         *         description="Created new course.",
         *         @SWG\Schema(ref="#/definitions/course")
         *     )
         * )
         */
        $controllers->post('/', 'service.course:newCourse');

        /**
         * @SWG\Get(
         *     path="/course/{courseId}",
         *     tags={"course"},
         *     @SWG\Parameter(name="courseId", type="integer", format="int32", in="path"),
         *     @SWG\Response(
         *         response="200",
         *         description="a course",
         *         @SWG\Schema(ref="#/definitions/course")
         *     )
         * )
         */
        $controllers->get('/{courseId}', 'service.course:getDetails');

        /**
         * @SWG\Put(
         *     path="/course/{courseId}/",
         *     tags={"course"},
         *     @SWG\Parameter(ref="#/parameters/id"),
         *     @SWG\Response(response="201", description="Changed Course.",
         *     @SWG\Schema(ref="#/definitions/course"))
         * )
         */
        $controllers->put('/{courseId}', 'service.course:changeCourse');


        /**
         * @SWG\Delete(
         *     path="/course/{courseId}",
         *     tags={"course"},
         *     @SWG\Response(response="200", description="Successfull deleted.")
         * )
         */
        $controllers->delete('/{courseId}', 'service.course:deleteCourse');

        $controllers->get('/{courseId}/appointment/','service.appmnt:getList');
        
        $controllers->post('/{courseId}/appointment/','service.appmnt:newAppointment');

        $controllers->get('/{courseId}/appointment/{appmntId}', 'service.appmnt:getDetails');

        $controllers->put('/{courseId}/appointment/{appmntId}', 'service.appmnt:changeAppmnt');

        $controllers->delete('/{courseId}/appointment/{appmntId}', 'service.appmnt:deleteAppmnt');


        $controllers->get('/{courseId}/subscribe', 'service.course:getSubscribers');

        $controllers->post('/{courseId}/subscribe','service.course:subscribe');
        $controllers->delete('/{courseId}/subscribe','service.course:unsubscribe');

        return $controllers;
    }
}
