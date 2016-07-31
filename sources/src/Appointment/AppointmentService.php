<?php


namespace HsBremen\WebApi\Appointment;


use HsBremen\WebApi\Course\CourseRepository;
use HsBremen\WebApi\Entity\Appointment;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppointmentService
{
    /** @var AppointmentRepository  */
    private $appointmentRepoistory;


    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepoistory = $appointmentRepository;
    }

    public function newAppointment(Request $request, $courseId)
    {
        $postData = $request->request->all();
        unset($postData['id']);
        try{
            $appmnt = Appointment::createFromArray($postData);
            $appmnt->setCourseid($courseId);
            $appmnt->checkIntegrity();
        } catch (\Exception $ex) {
            return new JsonResponse(['message' => $ex->getMessage()],400);
        }
        try{
            $this->appointmentRepoistory->saveNewAppointment($appmnt);
        } catch (\Exception $ex){
            return new JsonResponse(['message' => $ex->getMessage()],404);
        }
        return new JsonResponse($appmnt,200);
    }

    public function getList($courseId)
    {
        $appmnts = $this->appointmentRepoistory->getAllAppointsments($courseId);
        return new JsonResponse($appmnts);
    }
    
    public function getDetails($courseId, $appmntId)
    {
        try {
            $appmnt = $this->appointmentRepoistory->getAppointment($appmntId); 
        } catch (\Exception $ex) {
            return new JsonResponse(['message' => $ex->getMessage()],404);
        }
        return new JsonResponse($appmnt,200);
    }

    public function changeAppmnt($courseId, $appmntId, Request $request)
    {
        $postData = $request->request->all();
        unset($postData['id']);
        try{
            $appmnt = Appointment::createFromArray($postData);
            $appmnt->setCourseid($courseId);
            $appmnt->setId($appmntId);
            $appmnt->checkIntegrity();
        } catch (\Exception $ex) {
            return new JsonResponse(['message' => $ex->getMessage()],400);
        }
        try{
            $this->appointmentRepoistory->saveAppointment($appmnt);
        } catch (\Exception $ex){
            return new JsonResponse(['message' => $ex->getMessage()],404);
        }
        return new JsonResponse($appmnt,200);
    }

    public function deleteAppmnt($courseId, $appmntId)
    {
        $this->appointmentRepoistory->deleteAppointment($appmntId);
        return new JsonResponse([],200);
    }

}