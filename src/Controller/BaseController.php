<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /**
     * @Route("/", name="home_controller")
     */
    public function index()
    {
        return $this->render('base/home.html.twig', [
            'controller_name' => 'BaseController',
        ]);
    }
}
