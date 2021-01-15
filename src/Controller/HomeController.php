<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $page = 1;
        $tricks = $this->trickRepository->homeTricks($page);
        $count = $this->trickRepository->count(array());
        if(5 > $count){
            $page = 0;
        }
        return $this->render('home/home.html.twig', [
            'tricks' => $tricks,
            'page' => $page
        ]);
    }

    /**
     * @Route("/loadmore/{page}", name="load_more")
     */
    public function loadMore(int $page)
    {
        $tricks = $this->trickRepository->homeTricks($page);
        $count = $this->trickRepository->count([]);
        $response = [
            'page' => $page,
            'html' => $this->render(
                'home/loadmore.html.twig',
                [
                    'tricks' => $tricks
                ]
            )->getContent()
        ];

        if($page*5 > $count){
            $response['page'] = 0;
        }


        return new JsonResponse($response);
    }
}
