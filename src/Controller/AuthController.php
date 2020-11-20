<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Service\UserRegister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class AuthController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserRegister $register
     * @return Response
     */
    public function register(Request $request, UserRegister $register): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $register->register($form->getData(), $form->get('plainPassword')->getData() );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify", name="app_verify_mail")
     * @param Request $request
     * @param UserRegister $register
     * @return Response
     */
    public function verifyMail(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findByToken($request->get('token'));
        if(isset($user[0])){
            $user[0]->setToken(NULL);
            $entityManager->persist($user[0]);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
        }else{
            return $this->redirectToRoute('app_register');
        }
    }
}
