<?php

namespace App\Controller;

use App\Form\ContactUsType;
use App\Repository\JobPostMdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home.html.twig', [
        ]);
    }

    /**
     * @Route("/apply/{slug}", name="job_apply")
     */
    public function jobApply(JobPostMdRepository $mdRepo, $slug)
    {
        $job = $mdRepo->findOneBySlug($slug);

        return $this->redirect($job->getApplyUrl());
    }

    /**
     * @Route("/jobs", name="job_list")
     */
    public function jobList(JobPostMdRepository $mdRepo)
    {
        $jobs = $mdRepo->findBy();

        return $this->render('job_list.html.twig', [
            'pageTitle' => 'Jobs',
            'jobs' => $jobs,
        ]);
    }

    /**
     * @Route("/job", name="job_latest")
     */
    public function jobLatest()
    {
        //TODO: redirect to latest job post.
        return $this->redirectToRoute('job_post', ['slug' => 'job_post_2021']);
    }

    /**
     * @Route("/job/{slug}", name="job_post")
     */
    public function jobPost(JobPostMdRepository $mdRepo, $slug)
    {
        $job = $mdRepo->findOneBySlug($slug);

        return $this->render('job_post.html.twig', [
            'pageTitle' => $job->getTitle(),
            'job' => $job,
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request)
    {
        $form = $this->createForm(ContactUsType::class);

        if ($request->isMethod('POST')) {
            $form->submit($request->request->get($form->getName()));

            if ($form->isSubmitted() && $form->isValid()) {
                //TODO: send email.

                $this->addFlash('success', 'Message Submitted Successfully');

                return $this->redirectToRoute('contact');
            }
        }

        return $this->render('contact.html.twig', [
            'pageTitle' => 'Contact Us',
            'form' => $form->createView(),
        ]);
    }
}
