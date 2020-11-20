<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @Entity ("trick", expr="repository.findById(id)")
     */
    public function trick(Trick $trick)
    {

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick
        ]);
    }
}
