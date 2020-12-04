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
            $trick = new Trick();
            $trick->setName($form->get('name')->getData());
            $trick->setDescription($form->get('description')->getData());
            $trick->setCategory($form->get('category')->getData());
            $this->entityManager->persist($trick);

            foreach ($form['images'] as $image){
                $file = $image->get('path')->getData();
                if($file){
                    $newFilename = uniqid().'.'.$file->guessExtension();
                    try {
                        $file->move('images/uploads', $newFilename);
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $newImage = new Image();
                    $newImage->setPath($newFilename);
                    $newImage->setDescription($image->get('description')->getData());
                    $newImage->setTrick($trick);
                    $this->entityManager->persist($newImage);
                }
            }

            foreach ($form['videos'] as $video){
                $newVideo = new Video();
                $newVideo->setUrl($video->get('url')->getData());
                $newVideo->setDescription($video->get('description')->getData());
                $newVideo->setTrick($trick);
                $this->entityManager->persist($newVideo);
            }

            $this->entityManager->flush();
            dd($trick);
        }

        return $this->render('trick/createtrick.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
