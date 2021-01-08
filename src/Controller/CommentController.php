<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Trick;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/comment/{id}", name="comment")
     * @Entity ("trick", expr="repository.findById(id)")
     * @IsGranted("ROLE_USER")
     */
    public function comment(Request $request, Trick $trick)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setUser($this->getUser());
            $comment->setTrick($trick);
            $comment->setCreatedAt(new \DateTime());
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('trick', ['id' => $trick->getId()]);
    }

    /**
     * @Route("/deletecomment/{id}", name="delete_comment")
     * @Entity ("comment", expr="repository.findOneById(id)")
     * @IsGranted("ROLE_USER")
     */
    public function deleteComment(Request $request, Comment $comment)
    {
        $trick = $comment->getTrick();
        if($this->getUser() == $comment->getUser()){
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('trick', ['id' => $trick->getId()]);
    }
}
