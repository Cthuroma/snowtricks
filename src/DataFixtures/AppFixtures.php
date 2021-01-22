<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Group;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $verifiedUser = new User();
        $verifiedUser->setEmail('verified@snowtricks.net');
        $verifiedUser->setName('Verified User');
        $verifiedUser->setToken(null);
        $verifiedUser->setPassword($this->encoder->encodePassword(
            $verifiedUser,
            'verified'
        ));
        $manager->persist($verifiedUser);

        $unverifiedUser = new User();
        $unverifiedUser->setEmail('unverified@snowtricks.net');
        $unverifiedUser->setName('Unverified User');
        $unverifiedUser->setToken(md5(random_bytes(10)));
        $unverifiedUser->setPassword($this->encoder->encodePassword(
            $unverifiedUser,
            'unverified'
        ));
        $manager->persist($unverifiedUser);

        $group = new Group();
        $group->setName('Grabs');
        $group->setDescription('Figures that consist in grabbing one part of the snowboard with one or both hands.');
        $manager->persist($group);

        $group = new Group();
        $group->setName('Straight Airs');
        $group->setDescription('Aerial tricks without rotation.');
        $manager->persist($group);

        $group = new Group();
        $group->setName('Spins');
        $group->setDescription('Aerial rotations horizontally.');
        $manager->persist($group);

        $group = new Group();
        $group->setName('Flips');
        $group->setDescription('Aerial vertical rotations.');
        $manager->persist($group);

        $group = new Group();
        $group->setName('Slides');
        $group->setDescription('Slides are tricks performed along the surface of obstacles like handrails and funboxes. In skateboarding, slides are distinguished from grinds because some tricks are performed by sliding on the surface of the skateboard, and others are performed by grinding on the trucks of the skateboard. However, because snowboards don\'t have trucks, the term grind doesn\'t apply to these types of maneuvers. They can still be called grinds.');
        $manager->persist($group);

        for($i = 1; $i < 18; $i++){
            $trick = new Trick();
            $trick->setName('Nose Grab '.$i);
            $trick->setDescription('The front hand grabs the nose of the snowboard.');
            $trick->setCategory($group);

            $videoOne = new Video();
            $videoOne->setTrick($trick);
            $videoOne->setUrl('https://www.youtube.com/embed/gZFWW4Vus-Q');
            $videoOne->setDescription('How To Nose Grab - Snowboarding Tricks');

            $videoTwo = new Video();
            $videoTwo->setTrick($trick);
            $videoTwo->setUrl('https://www.youtube.com/embed/nIS14rVlbyQ');
            $videoTwo->setDescription('Common Mistakes With Nose Grabs');

            $trick->addVideo($videoOne);
            $trick->addVideo($videoTwo);

            $imageOne = new Image();
            $imageOne->setTrick($trick);
            $imageOne->setPath('fixture1.jpg');
            $imageOne->setDescription('Nose grab Image');

            $imageTwo = new Image();
            $imageTwo->setTrick($trick);
            $imageTwo->setPath('fixture2.jpg');
            $imageTwo->setDescription('Nose grab Image');

            $trick->addImage($imageOne);
            $trick->addImage($imageTwo);

            $comment = new Comment();
            $comment->setContent('Harder to do than it looks like !');
            $comment->setUser($verifiedUser);
            $comment->setTrick($trick);
            $comment->setCreatedAt(new \DateTime());

            $trick->addComment($comment);

            $manager->persist($trick);
            $manager->persist($comment);
            $manager->persist($videoOne);
            $manager->persist($videoTwo);
            $manager->persist($imageOne);
            $manager->persist($imageTwo);
        }
        $manager->flush();
    }
}
