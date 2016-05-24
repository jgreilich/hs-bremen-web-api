<?php

namespace HsBremen\WebApi\Course;

use Silex\Application;
use Silex\ServiceProviderInterface;

class CourseServiceProvider implements ServiceProviderInterface
{
    /** {@inheritdoc} */
    public function register(Application $app)
    {
        $app['repo.course'] = $app->share(function (Application $app) {
            return new CourseRepository($app['db']);
        });
        
        $app['service.course'] = $app->share(function () {
            return new CourseService();
        });

        $app->mount('/course', new CourseRoutesProvider());
    }

    /** {@inheritdoc} */
    public function boot(Application $app)
    {
        /** @var CourseRepository $repo */
        $repo = $app['repo.course'];
        $repo->CreateTables();
    }
}
