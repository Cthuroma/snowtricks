<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick", name="trick")
     */
    public function trick()
    {
        return $this->render('trick/trick.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }
}
