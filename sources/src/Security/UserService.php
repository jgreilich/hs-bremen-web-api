<?php


namespace HsBremen\WebApi\Security;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;

class UserService
{
    /** @var  UserProvider */
    private $userRepository;

    /**
     * UserService constructor.
     * @param UserProvider $userRepository
     */
    public function __construct(UserProvider $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * POST /register
     * 
     * @param Request $request
     */
    public function registerUser(Request $request)
    {
        $username = $request->request->get('username',false);
        $password = $request->request->get('password',false);
        
        if($username && $password){
            $this->userRepository->saveNewUser();
        }
    }
}