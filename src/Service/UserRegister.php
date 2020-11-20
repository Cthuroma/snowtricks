<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class UserRegister
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

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
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

}
