<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
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
     * @Route("/", name="home")
     */
    public function home(Request $request)
    {
        $tricks = $this->trickRepository->homeTricks($request->query->get('page', 1));

        return $this->render('home/home.html.twig', [
            'tricks' => $tricks
        ]);
    }


}
