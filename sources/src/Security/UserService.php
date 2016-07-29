<?php


namespace HsBremen\WebApi\Security;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @return Response
     */
    public function registerUser(Request $request)
    {
        $username = $request->request->get('username',false);
        $password = $request->request->get('password',false);
        
        if($username && $password){
            try {
                $this->userRepository->saveNewUser($username,$password);
                return new Response("User registered.",201);
            } catch( \Exception $ex) {
                return new Response("Error: " . $ex->getMessage(),400);
            }
        } else {
            return new Response("User-Data required.",412);
        }
    }
    
}