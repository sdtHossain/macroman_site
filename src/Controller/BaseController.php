<?php

namespace App\Controller;

use App\Form\ContactUsType;
use App\Repository\JobPostMdRepository;
use Leogout\Bundle\SeoBundle\Provider\SeoGeneratorProvider;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /** @var SeoGeneratorProvider */
    private $seo;

    /** @var Request */
    private $request;

    /** @var Packages */
    private $assetPackages;

    public function __construct(SeoGeneratorProvider $seo, RequestStack $requestStack, Packages $assetPackages)
    {
        $this->seo = $seo;
        $this->request = $requestStack->getCurrentRequest();
        $this->assetPackages = $assetPackages;
    }

    private function setSeo($title, $description)
    {
        if (null != $title) {
            $this->seo->get('basic')
                ->setTitle($title);

            $this->seo->get('og')
                ->setTitle($title);

            $this->seo->get('twitter')
                ->setTitle($title);
        }
        if (null != $description) {
            $this->seo->get('basic')
                ->setDescription($description);

            $this->seo->get('og')
                ->setDescription($description);

            $this->seo->get('twitter')
                ->setDescription($description);
        }

        $image = $this->request->getUriForPath($this->assetPackages->getUrl('build/img/home/og-min-wide.png'));
        $uri = $this->request->getUri();

        $this->seo->get('og')
            ->setImage($image)
            ->setUrl($uri);

        $this->seo->get('twitter')
            ->setImage($image);
    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $this->setSeo(null, null);

        return $this->render('home.html.twig', [
        ]);
    }

    /**
     * @Route("/apply/{slug}", name="job_apply")
     */
    public function jobApply(Request $request, JobPostMdRepository $mdRepo, $slug)
    {
        $job = $mdRepo->findOneBySlug($slug);

        return $this->redirect($job->getApplyUrl());
    }

    /**
     * @Route("/jobs", name="job_list")
     */
    public function jobList(Request $request, JobPostMdRepository $mdRepo)
    {
        $jobs = $mdRepo->findBy();

        $this->setSeo('Jobs - Macroman Solutions', null);

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
    public function jobPost(Request $request, JobPostMdRepository $mdRepo, $slug)
    {
        $job = $mdRepo->findOneBySlug($slug);

        $this->setSeo($job->getTitle().' - Macroman Solutions', null);

        return $this->render('job_post.html.twig', [
            'pageTitle' => $job->getTitle(),
            'job' => $job,
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer, string $adminEmail, string $senderEmail)
    {
        $this->setSeo('Contact Us - Macroman Solutions', null);

        $form = $this->createForm(ContactUsType::class);

        if ($request->isMethod('POST')) {
            $form->submit($request->request->get($form->getName()));

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                $mailer->send(
                    (NotificationEmail::asPublicEmail())
                    ->subject($data['subject'])
                    ->htmlTemplate('@email/default/notification/body.html.twig')
                    ->from($senderEmail)
                    ->to(...\explode(',', $adminEmail))
                    ->context([
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
