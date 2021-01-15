<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class UserAuth
{
    /**
     * @var UserPasswordEncoder
     */
    private $encoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, MailerInterface $mailer, UserRepository $userRepository)
    {
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    public function register(User $user, string $plainPassword)
    {

        $user->setPassword(
            $this->encoder->encodePassword(
                $user,
               $plainPassword
            )
        );
        $user->setToken(md5(random_bytes(10)));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $email = (new TemplatedEmail())
            ->from('admin@snowtricks.com')
            ->to(new Address($user->getEmail()))
            ->subject('Thanks for signing up!')
            ->htmlTemplate('emails/register.html.twig')
            ->context([
                'user' => $user]);

        $this->mailer->send($email);
        return $user;
    }

    public function resetPass(string $mail)
    {

        $user = $this->userRepository->findByMail($mail);
        if(isset($user)){
            $user->setToken(md5(random_bytes(10)));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $email = (new TemplatedEmail())
                ->from('admin@snowtricks.com')
                ->to(new Address($user->getEmail()))
                ->subject('Resetting your password')
                ->htmlTemplate('emails/reset.html.twig')
                ->context([
                              'user' => $user]);

            $this->mailer->send($email);
        }
        return true;
    }

    public function resetPassLast(string $token, string $plainPassword)
    {

        $user = $this->userRepository->findByToken($token);
        if($user !== null){
            $user->setPassword(
                $this->encoder->encodePassword(
                    $user,
                    $plainPassword
                )
            );
            $user->setToken(null);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return true;
    }

}
