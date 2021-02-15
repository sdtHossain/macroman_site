<?php

namespace App\Controller;

use App\Form\ContactUsType;
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
     * @Route("/apply", name="apply")
     */
    public function jobApply()
    {
        return $this->redirect('https://forms.gle/KvbHimutz3jgQsH8A');
    }

    /**
     * @Route("/jobs", name="job_list")
     */
    public function jobList()
    {
        return $this->render('job_list.html.twig', [
            'pageTitle' => 'Jobs',
        ]);
    }

    /**
     * @Route("/job", name="job_latest")
     */
    public function jobLatest()
    {
        //TODO: redirect to latest job post.
        return $this->redirectToRoute('job_post', ['slug' => 'foo']);
    }

    /**
     * @Route("/job/{slug}", name="job_post")
     */
    public function jobPost()
    {
        return $this->render('job_post.html.twig', [
            'pageTitle' => 'Software Engineer Recruitment',
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
