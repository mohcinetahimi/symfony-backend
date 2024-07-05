<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route(path: "/api/login",name: "api_login",methods: ["POST"])]
    public function ApiLogin(){
        $user= $this->getUser();

        $userData = [
            'email' => $user->getEmail(),
            'name' => $user->getName(),
        ];

        return new JsonResponse(json_encode($userData,JSON_THROW_ON_ERROR));

}
}