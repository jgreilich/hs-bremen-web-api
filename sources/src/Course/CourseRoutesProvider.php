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
         * @SWG\Tag(name="course", description="All about courses")
         * @SWG\Tag(name="appointment", description="All about appointments of courses")
         * @SWG\Tag(name="subscribe", description="All about subscription to courses")
         */


        /**
         * @SWG\Get(
         *     path="/course/",
         *     tags={"course"},
         *     @SWG\Response(response="200", description="List of Courses")
         * )
         */
        $controllers->get('/', 'service.course:getList'); // ok

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
        $controllers->post('/', 'service.course:newCourse'); // ok

        /**
         * @SWG\Get(
         *     path="/course/{courseId}",
         *     tags={"course"},
         *     @SWG\Parameter(name="courseId", type="integer", in="path"),
         *     @SWG\Response(
         *         response="200",
         *         description="a course",
         *         @SWG\Schema(ref="#/definitions/course")
         *     )
         * )
         */
        $controllers->get('/{courseId}', 'service.course:getDetails'); // ok

        /**
         * @SWG\Put(
         *     path="/course/{courseId}",
         *     tags={"course"},
         *     @SWG\Parameter(name="courseId", type="integer", in="path"),
         *     @SWG\Parameter(name="course", in="body", @SWG\Schema(ref="#/definitions/course")),
         *     @SWG\Response(response="201", description="Changed Course.",
         *     @SWG\Schema(ref="#/definitions/course"))
         * )
         */
        $controllers->put('/{courseId}', 'service.course:changeCourse'); // ok

        /**
         * @SWG\Delete(
         *     path="/course/{courseId}",
         *     tags={"course"},
         *     @SWG\Parameter(name="courseId", type="integer", in="path"),
         *     @SWG\Response(response="200", description="Successfull deleted.")
         * )
         */
        $controllers->delete('/{courseId}', 'service.course:deleteCourse'); // ok

        /**
         * @SWG\Get(
         *     path="/course/{courseId}/appointment/",
         *     tags={"appointment"},
         *     @SWG\Parameter(name="courseId", type="integer", in="path"),
         *     @SWG\Response(response="200", description="List of appointsments")
         * )
         */
        $controllers->get('/{courseId}/appointment/','service.appmnt:getList'); // ok

        /**
         * @SWG\Post(
         *     path="/course/{courseId}/appointment/",
         *     tags={"appointment"},
         *     @SWG\Parameter(name="courseId", type="integer", in="path"),
         *     @SWG\Parameter(name="appointment", in="body", @SWG\Schema(ref="#/definitions/appointment")),
         *     @SWG\Response(
         *     response="201",
         *     description="Appointment Created",
         *     @SWG\Schema(ref="#/definitions/appointment"))
         * )
         */
        $controllers->post('/{courseId}/appointment/','service.appmnt:newAppointment'); // ok

        /**
         * @SWG\Get(
         *     path="/course/{courseId}/appointment/{appmntId}",
         *     tags={"appointment"},
         *     @SWG\Parameter(name="courseId", type="integer", in="path"),
         *     @SWG\Parameter(name="appmntId", type="integer", in="path"),
         *     @SWG\Response(response="200", description="List of appointsments", @SWG\Schema(ref="#/definitions/appointment"))
         * )
         */
        $controllers->get('/{courseId}/appointment/{appmntId}', 'service.appmnt:getDetails'); // ok

        /**
         * @SWG\Put(
         *     path="/course/{courseId}/appointment/{appmntId}",
         *     tags={"appointment"},
         *     @SWG\Parameter(name="courseId", type="integer", in="path"),
         *     @SWG\Parameter(name="appmntId", type="integer", in="path"),
         *     @SWG\Parameter(name="appointment", in="body", @SWG\Schema(ref="#/definitions/appointment")),
         *     @SWG\Response(
         *     response="201",
         *     description="Appointment Changed",
         *     @SWG\Schema(ref="#/definitions/appointment"))
         * )
         */
        $controllers->put('/{courseId}/appointment/{appmntId}', 'service.appmnt:changeAppmnt'); // ok

        /**
         * @SWG\Delete(
         *     path="/course/{courseId}/appointment/{appmntId}",
         *     tags={"appointment"},
         *     @SWG\Parameter(name="courseId", type="integer", in="path"),
         *     @SWG\Parameter(name="appmntId", type="integer", in="path"),
         *     @SWG\Response(response="200", description="Successfull deleted."))
         * )
         */
        $controllers->delete('/{courseId}/appointment/{appmntId}', 'service.appmnt:deleteAppmnt');

        /**
         * @SWG\Get(
         *     path="/course/{courseId}/subscribe",
         *     tags={"subscribe"},
         *     @SWG\Parameter(name="courseId", type="integer", in="path"),
         *     @SWG\Response(response="200", description="List of appointsments")
         * )
         */
        $controllers->get('/{courseId}/subscribe', 'service.course:getSubscribers');

        /**
         * @SWG\Post(
         *     path="/course/{courseId}/subscribe",
         *     tags={"subscribe"},
         *     @SWG\Parameter(name="courseId", type="integer", in="path"),
         *     @SWG\Response(response="200", description="subscribed to course")
         * )
         */
        $controllers->post('/{courseId}/subscribe','service.course:subscribe');

        /**
         * @SWG\Delete(
         *     path="/course/{courseId}/subscribe",
         *     tags={"subscribe"},
         *     @SWG\Parameter(name="courseId", type="integer", in="path"),
         *     @SWG\Response(response="200", description="unsubscribed from course")
         * )
         */
        $controllers->delete('/{courseId}/subscribe','service.course:unsubscribe');

        return $controllers;
    }
}
