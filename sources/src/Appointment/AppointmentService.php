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
        $appmnt = Appointment::createFromArray($postData);
        $appmnt->setCourseid($courseId);
        $appmnt->checkIntegrity();
        $this->appointmentRepoistory->saveNewAppointment($appmnt);
        return new JsonResponse($appmnt);
    }

    public function getList($courseId)
    {
        $appmnts = $this->appointmentRepoistory->getAllAppointsments($courseId);
        return new JsonResponse($appmnts);
    }
    
    public function getDetails($courseId, $appmntId)
    {
        $appmnt = $this->appointmentRepoistory->getAppointment($appmntId);
        return new JsonResponse($appmnt);
    }

    public function changeAppmnt($courseId, $appmntId, Request $request)
    {
        $postData = $request->request->all();
        unset($postData['id']);
        $appmnt = Appointment::createFromArray($postData);
        $appmnt->setCourseid($courseId);
        $appmnt->setId($appmntId);
        $appmnt->checkIntegrity();

        $this->appointmentRepoistory->saveAppointment($appmnt);
        return new JsonResponse($appmnt,200);
    }

    public function deleteAppmnt($courseId, $appmntId)
    {
        $this->appointmentRepoistory->deleteAppointment($appmntId);
        return new JsonResponse([],200);
    }

}