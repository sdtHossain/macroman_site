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
     * @Route("/jobs", name="job")
     */
    public function job()
    {
        return $this->render('base/job.html.twig');
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
