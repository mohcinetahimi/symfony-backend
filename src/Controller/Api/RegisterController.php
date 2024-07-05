<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register')]
    public function register(ValidatorInterface $validator,SerializerInterface $serializer,Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()){
            return new JsonResponse($serializer->serialize(['message'=>'logout first to register'],'json'),Response::HTTP_UNAUTHORIZED);
        }
        $newUser = $serializer->deserialize($request->getContent(), User::class, 'json');
        dump($newUser);

        // Or log to PHP error log
        error_log(print_r($newUser, true));
        $errors = $validator->validate($newUser);

        if($errors->count() > 0){
            return new JsonResponse($serializer->serialize($errors,'json'),Response::HTTP_BAD_REQUEST);
        }
        $password = $newUser->getPassword();

        // encode the plain password
        $newUser->setPassword(
            $userPasswordHasher->hashPassword(
                $newUser,
                $password
            )
        );

        $entityManager->persist($newUser);
        $entityManager->flush();

        // do anything else you need here, like send an email
        return new JsonResponse($serializer->serialize(['message'=>'Your account has been  created'],'json'),Response::HTTP_OK);

    }

}