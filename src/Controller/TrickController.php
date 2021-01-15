<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\AddTrickAssetType;
use App\Form\CommentType;
use App\Form\EditTrickType;
use App\Form\TrickFormType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/trick/{id<\d+>}-{slug}", name="trick")
     * @Entity ("trick", expr="repository.findById(id)")
     */
    public function trick(Trick $trick)
    {
        $form = $this->createForm(CommentType::class);
        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/editrick/{id}", name="editrick")
     * @Entity ("trick", expr="repository.findById(id)")
     * @IsGranted("ROLE_USER")
     */
    public function trickEdit(Trick $trick)
    {
        $assetForm = $this->createForm(AddTrickAssetType::class);
        $form = $this->createForm(EditTrickType::class, $trick);
        return $this->render('trick/editrick.html.twig', [
            'trick' => $trick,
            'assetForm' => $assetForm->createView(),
            'form' => $form->createView()
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
     * @Route("/delete/trick/{id}", name="trick.delete")
     * @Entity ("trick", expr="repository.findById(id)")
     * @IsGranted("ROLE_USER")
     */
    public function deleteTrick(Trick $trick)
    {
        $this->entityManager->remove($trick);
        $this->entityManager->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/create/trick", name="trick.create")
     * @IsGranted("ROLE_USER")
     */
    public function createTrick(Request $request)
    {
        $trick = new Trick();

        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($trick);
            $this->entityManager->flush();
        }

        return $this->render('trick/createtrick.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/addAsset/trick/{id}", name="add_trick_asset")
     * @IsGranted("ROLE_USER")
     */
    public function addTrickAsset(Request $request, Trick $trick)
    {
        $assetForm = $this->createForm(AddTrickAssetType::class);
        $assetForm->handleRequest($request);

        if ($assetForm->isSubmitted() && $assetForm->isValid()) {
            $images = $assetForm->getData()['images'];
            foreach($images as $image){
                $trick->addImage($image);
            }
            foreach($assetForm->getData()['videos'] as $video){
                $trick->addVideo($video);
            }
           $this->entityManager->persist($trick);
           $this->entityManager->flush();
            return $this->redirectToRoute('editrick', ['id' => $trick->getId()]);
        }

        $form = $this->createForm(EditTrickType::class);

        return $this->render('trick/editrick.html.twig', [
            'trick' => $trick,
            'assetForm' => $assetForm->createView(),
            'form' => $form->createView(),
            'assetError' => true
        ]);
    }

    /**
     * @Route("/deleteImage/{id}", name="delete_image")
     * @IsGranted("ROLE_USER")
     */
    public function deleteImage(Image $image)
    {
        $trick = $image->getTrick();
        $this->entityManager->remove($image);
        $this->entityManager->flush();
        return $this->redirectToRoute('editrick', ['id' => $trick->getId()]);
    }

    /**
     * @Route("/deleteVideo/{id}", name="delete_video")
     * @IsGranted("ROLE_USER")
     */
    public function deleteVideo(Video $video)
    {
        $trick = $video->getTrick();
        $this->entityManager->remove($video);
        $this->entityManager->flush();
        return $this->redirectToRoute('editrick', ['id' => $trick->getId()]);
    }

    /**
     * @Route("/edit/trick/{id}", name="edit_trick")
     * @IsGranted("ROLE_USER")
     */
    public function editTrick(Request $request, Trick $trick)
    {
        $form = $this->createForm(EditTrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($trick);
            $this->entityManager->flush();
            return $this->redirectToRoute('editrick', ['id' => $trick->getId()]);
        }

        $assetForm = $this->createForm(AddTrickAssetType::class);

        return $this->render('trick/editrick.html.twig', [
            'trick' => $trick,
            'assetForm' => $assetForm->createView(),
            'form' => $form->createView(),
            'editError' => true
        ]);
    }
}
