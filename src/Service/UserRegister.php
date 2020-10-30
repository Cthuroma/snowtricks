<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager)
    {
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
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
        // TODO do anything else you need here, like send an email

        return $user;
    }

}
