<?php


use HsBremen\WebApi\Course\CourseRepository;
use HsBremen\WebApi\Course\CourseService;
use HsBremen\WebApi\Entity\Course;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\User\User;

class CourseServiceTest extends PHPUnit_Framework_TestCase
{
    /** @var PHPUnit_Framework_MockObject_MockObject*/
    private $courseRepository;

    /** @var  CourseService */
    private $courseService;
    
    /** @var  PHPUnit_Framework_MockObject_MockObject */
    private $tokenStorage;
    
    /** @var  PHPUnit_Framework_MockObject_MockObject */
    private $request;

    /** @var PHPUnit_Framework_MockObject_MockObject requestParam */
    private $requestParam;

    public function setUp()
    {
        $this->courseRepository = self::getMockBuilder(CourseRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->tokenStorage = self::getMockBuilder(TokenStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->courseService = new CourseService($this->courseRepository,$this->tokenStorage);
        
    }
    
    public function setUpRequest()
    {
        $this->request = self::getMockBuilder(Request::class)
        ->disableOriginalConstructor()
        ->getMock();
        
        /** @var PHPUnit_Framework_MockObject_MockObject request */
        $this->request->request = self::getMockBuilder(ParameterBag::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->requestParam = $this->request->request;
        
        
    }

    public function setUpToken()
    {
        
        $token = self::getMockBuilder(Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($token);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn(new User('user1','hispw'));
    }
    
    /**
     * @test
     */
    public function getListShouldReturn200()
    {
        $this->courseRepository
            ->expects($this->once())
            ->method('getAllCourses');
        
        /** @var \Symfony\Component\HttpFoundation\JsonResponse $response */
        $response = $this->courseService->getList();
        
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    
    /**
     * @test
     */
    public function shouldCreateNewCourse()
    {
        $course_data = [
            'id' => 1,
            'owner' => 'owner1',
            'name' => 'course1'
        ];
        
        $this->setUpRequest();
        $this->setUpToken();
        
        $this->requestParam
            ->expects($this->once())
            ->method('all')
            ->willReturn($course_data);
        
        $this->courseRepository
            ->expects($this->once())
            ->method('saveNewCourse');
            
        $response = $this->courseService->newCourse($this->request);
        
        $this->assertEquals(201,$response->getStatusCode());
    }


    /**
     * @test
     */
    public function shouldChangeCourse()
    {
        $course_data = [
            'id' => 1,
            'owner' => 'user1',
            'name' => 'course1'
        ];

        $this->setUpRequest();
        $this->setUpToken();

        $this->requestParam
            ->expects($this->once())
            ->method('all')
            ->willReturn($course_data);

        $this->courseRepository
            ->expects($this->once())
            ->method('getCourse')
            ->willReturn(Course::createFromArray($course_data));
        
        $this->courseRepository
            ->expects($this->once())
            ->method('saveCourse');

        $response = $this->courseService->changeCourse($this->request,1);

        $this->assertEquals(201,$response->getStatusCode());
    }
    
    
    /**
     * @test
     */
    public function shouldNotAllowChangeCourse()
    {
        $course_data = [
            'id' => 1,
            'owner' => 'notallowedUser',
            'name' => 'course1'
        ];

        $this->setUpRequest();
        $this->setUpToken();

        $this->requestParam
            ->expects($this->once())
            ->method('all')
            ->willReturn($course_data);

        $this->courseRepository
            ->expects($this->once())
            ->method('getCourse')
            ->willReturn(Course::createFromArray($course_data));

        $response = $this->courseService->changeCourse($this->request,1);

        $this->assertEquals(403,$response->getStatusCode());
        
    }
    



}
