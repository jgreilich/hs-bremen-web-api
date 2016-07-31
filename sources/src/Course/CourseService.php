<?php

namespace HsBremen\WebApi\Course;

use HsBremen\WebApi\Database\DatabaseException;
use HsBremen\WebApi\Entity\Course;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\User\User;

class CourseService
{
    /** @var CourseRepository $courseRepository */
    private $courseRepository;
    
    /** @var TokenStorage  */
    private $tokenStorage;
    
    public function __construct(CourseRepository $courseRepository, TokenStorage $tokenStorage)
    {
        $this->courseRepository = $courseRepository;
        $this->tokenStorage = $tokenStorage;
    }
    
    public function getList()
    {
        $courses_tmp = $this->courseRepository->getAllCourses();
        return new JsonResponse($courses_tmp,200);
    }

    public function newCourse(Request $request)
    {
        $postData = $request->request->all();
        unset($postData['id']);
        unset($postData['owner']);
        $course = Course::createFromArray($postData);
        $course->setOwner($this->getUsername());
        $this->courseRepository->saveNewCourse($course);
        return new JsonResponse($course,201);
    }
    

    public function getDetails($courseId)
    {
        try{
            $course_tmp = $this->courseRepository->getCourse($courseId);
            return new JsonResponse($course_tmp);
        } catch (DatabaseException $ex){
            return new JsonResponse(['message' => $ex->getMessage()],404);
        }
    }


    public function changeCourse(Request $request, $courseId)
    {
        $postData = $request->request->all();
        unset($postData['id']);
        unset($postData['owner']);

        try{
            $course = $this->courseRepository->getCourse($courseId);
        } catch (DatabaseException $ex){
            return new JsonResponse(['message' => $ex->getMessage()],404);
        }
        
        if($course->getOwner() == $this->getUsername()){
            $course->setName($postData['name']);
            $this->courseRepository->saveCourse($course);
            return new JsonResponse($course,201);
        } else {
            return new JsonResponse(['message' => 'Not Owner of this Course!'],403);
        }
    }

    public function deleteCourse($courseId)
    {
        try{
            $course = $this->courseRepository->getCourse($courseId);
        } catch (DatabaseException $ex){
            return new JsonResponse(['message' => $ex->getMessage()],404);
        }
        if($course->getOwner() == $this->getUsername()){
            $this->courseRepository->deleteCourse($courseId);
            return new JsonResponse([],200);
        } else {
            return new JsonResponse(['message' => 'Not Owner of this Course!'],403);
        }
    }

    public function subscribe($courseId)
    {
        try{
            $this->courseRepository->subscribeCourse($this->getUsername(),$courseId);
            return new JsonResponse([],200);
        } catch (\Exception $ex){
            return new JsonResponse(['message' => $ex->getMessage()],400);
        }
    }

    public function unsubscribe($courseId)
    {
        $this->courseRepository->unsubscribeCourse($this->getUsername(),$courseId);
        return new JsonResponse([],200);
    }

    public function getSubscribers($courseId)
    {
        return new JsonResponse($this->courseRepository->getSubscribers($courseId), 200);
    }

    private function getUsername()
    {
        $token = $this->tokenStorage->getToken();
        if(null !== $token) {
            /** @var User $user */
            $user = $token->getUser();
            return $user->getUsername();
        }
        else
        {
            return "";    // TODO: Throw Exception Not Authorized
        }
    }
    
}