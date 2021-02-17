<?php

namespace App\Controller;

use App\Form\ContactUsType;
use App\Repository\JobPostMdRepository;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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
        return $this->redirectToRoute('job_post', ['slug' => 'software_engineer_q1_21']);
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
    public function contact(Request $request, MailerInterface $mailer, string $adminEmail)
    {
        $form = $this->createForm(ContactUsType::class);

        if ($request->isMethod('POST')) {
            $form->submit($request->request->get($form->getName()));

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                $mailer->send(
                    (NotificationEmail::asPublicEmail())
                    ->subject($data['subject'])
                    ->htmlTemplate('@email/default/notification/body.html.twig')
                    ->from($adminEmail)
                    ->to($adminEmail)
                    ->context([
                        'importance' => null,
                        'content' => $data['message'],
                        'footer_text' => 'Website Contact from: '.$data['name'].' <'.$data['email'].'>',
                    ])
                );

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
