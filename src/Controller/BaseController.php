<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /**
     * @Route("/", name="base_home")
     */
    public function index()
    {
        return $this->render('base/home.html.twig', [
            'controller_name' => 'BaseController',
        ]);
    }

    /**
     * @Route("/apply", name="home_apply")
     */
    public function jobApply()
    {
        return $this->redirect("https://docs.google.com/forms/d/e/1FAIpQLSdTDfoc_A0l7kuzL8wXl4fsxxDQtOgWLjFo1nNB2e4mdihsIQ/viewform?usp=sf_link");
    }

    /**
     * @Route("/jobs", name="home_job")
     */
    public function job()
    {
        return $this->render('base/job.html.twig');
    }
}
