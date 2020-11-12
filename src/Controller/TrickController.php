<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    public function __construct(TrickRepository $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    /**
     * @Route("/trick/{id}", name="trick")
     */
    public function trick(int $id)
    {
        $trick = $this->trickRepository->findById($id);

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick
        ]);
    }
}
