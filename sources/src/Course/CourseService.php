<?php

namespace HsBremen\WebApi\Course;

use HsBremen\WebApi\Entity\Course;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CourseService
{

    public function getList()
    {

        $courses_tmp = [
            new Course(2),
            new Course(122),
            new Course(125),
        ];
        return new JsonResponse($courses_tmp);

    }


    public function getDetails($courseId)
    {
        $course_tmp = new Course(1);
        return new JsonResponse($course_tmp);
    }
    
}