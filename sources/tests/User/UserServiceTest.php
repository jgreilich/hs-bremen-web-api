<?php


use HsBremen\WebApi\Security\UserProvider;
use HsBremen\WebApi\Security\UserService;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class UserServiceTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * @test
     */
    public function shouldReturn201()
    {
        $userRepo = self::getMockBuilder(UserProvider::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $request = self::getMock(Request::class);
        $request->request = self::getMock(ParameterBag::class);
        
        $request->request
            ->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(
                ['username',false],
                ['password',false])
            ->willReturnOnConsecutiveCalls(
                'Mustermann',
                'HisPW');
        
        $userRepo
            ->expects($this->once())
            ->method('saveNewUser');
        
        /** @var UserService $service */
        $service = new UserService($userRepo);
        
        $answer = $service->registerUser($request);
        $this->assertEquals(201, $answer->getStatusCode(),$answer->getContent());
    }
    
    /**
     * @test
     */
    public function shouldRespondWith412()
    {
        $userRepo = self::getMockBuilder(UserProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request = self::getMock(Request::class);
        $request->request = self::getMock(ParameterBag::class);

        $request->request
            ->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(
                ['username',false],
                ['password',false])
            ->willReturnOnConsecutiveCalls(
                false,
                false);

        /** @var UserService $service */
        $service = new UserService($userRepo);

        
        $answer = $service->registerUser($request);
        $this->assertEquals(412, $answer->getStatusCode(),$answer->getContent());
    }

}
