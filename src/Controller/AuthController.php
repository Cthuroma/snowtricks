<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Form\ResetPassFormType;
use App\Form\ResetPassInitFormType;
use App\Service\UserAuth;
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
     * @param UserAuth $register
     * @return Response
     */
    public function register(Request $request, UserAuth $register): Response
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
     * @param UserAuth $register
     * @return Response
     */
    public function verifyMail(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findByToken($request->get('token'));
        if(isset($user)){
            $user->setToken(NULL);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully confirmed your email.');
            return $this->redirectToRoute('app_login');
        }else{
            $this->addFlash('danger', 'Email confirmation didn\'t work.');
            return $this->redirectToRoute('app_register');
        }
    }


    /**
     * @Route("/reset", name="reset_password")
     * @return Response
     */
    public function resetPass(Request $request, UserAuth $resetPass): Response
    {
        if($request->get('token', false)){
            $form = $this->createForm(ResetPassFormType::class);
            $form->get('token')->setData($request->get('token'));
            return $this->render('security/reset.html.twig', ['form' => $form->createView()]);
        }
        $form = $this->createForm(ResetPassInitFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $resetPass->resetPass($form->get('email')->getData());

            return $this->redirectToRoute('app_login');
        }

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/resetpassword", name="app_reset_pass")
     * @return Response
     */
    public function resetPassLast(Request $request, UserAuth $resetPass): Response
    {

        $form = $this->createForm(ResetPassFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($resetPass->resetPassLast($form->get('token')->getData(), $form->get('plainPassword')->getData())){
                $this->addFlash('success', 'Successfully resetted your password.');
            }else{
                $this->addFlash('danger', 'An Error Occured.');
            }
            return $this->redirectToRoute('app_login');
        }
        return $this->redirectToRoute('reset_password', ['token' => $form->get('token')->getData()]);
    }
}
