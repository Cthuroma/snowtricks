<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\ImageType;
use App\Form\TrickFormType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class TrickController extends AbstractController
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(TrickRepository $trickRepository, EntityManagerInterface $entityManager)
    {
        $this->trickRepository = $trickRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/trick/{id}", name="trick")
     * @Entity ("trick", expr="repository.findById(id)")
     */
    public function trick(Trick $trick)
    {

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/tricks", name="tricks")
     * @Entity ("trick", expr="repository.findById(id)")
     */
    public function tricks(Trick $trick)
    {

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/create/trick", name="trick.create")
     */
    public function createTrick(Request $request)
    {
        $trick = new Trick();

        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($trick);
            $this->entityManager->persist($trick);
            $this->entityManager->flush();
        }

        return $this->render('trick/createtrick.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
