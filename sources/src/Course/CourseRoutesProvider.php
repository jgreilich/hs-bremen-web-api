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
         * @SWG\Parameter(name="id", type="integer", format="int32", in="path")
         * @SWG\Tag(name="course", description="All about courses")
         */

        /**
         * @SWG\Get(
         *     path="/course",
         *     tags={"course"},
         *     @SWG\Response(response="200", description="An example resource")
         * )
         */
        $controllers->get('', 'service.course:getList');
        /**
         * @SWG\Get(
         *     path="/course/{id}",
         *     tags={"course"},
         *     @SWG\Parameter(ref="#/parameters/id"),
         *     @SWG\Response(
         *         response="200",
         *         description="An example resource",
         *          @SWG\Schema(ref="#/definitions/course")
         *     )
         * )
         */
        $controllers->get('/{courseId}', 'service.course:getDetails');

        return $controllers;
    }
}
