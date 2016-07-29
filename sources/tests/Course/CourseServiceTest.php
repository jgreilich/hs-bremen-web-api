<?php


use HsBremen\WebApi\Course\CourseRepository;
use HsBremen\WebApi\Course\CourseService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CourseServiceTest extends PHPUnit_Framework_TestCase
{
    /** @var PHPUnit_Framework_MockObject_MockObject*/
    private $courseRepository;

    /** @var  CourseService */
    private $courseService;
    
    /** @var  TokenStorage */
    private $tokenStorage;
    
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



}
