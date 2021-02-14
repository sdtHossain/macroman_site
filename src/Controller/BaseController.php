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
        return $this->render('home.html.twig', [
            'page' => 'home',
        ]);
    }

    /**
     * @Route("/apply", name="home_apply")
     */
    public function jobApply()
    {
        return $this->redirect('https://forms.gle/KvbHimutz3jgQsH8A');
    }

    /**
     * @Route("/jobs", name="home_job")
     */
    public function job()
    {
        return $this->render('base/job.html.twig');
    }
}
