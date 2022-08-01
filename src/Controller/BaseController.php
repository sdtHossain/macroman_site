<?php

namespace App\Controller;

use App\Entity\ContactUsMessage;
use App\Form\ContactUsType;
use App\Repository\JobPostMdRepository;
use App\Service\SpamChecker;
use Leogout\Bundle\SeoBundle\Provider\SeoGeneratorProvider;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/about", name="about")
     */
    public function about(Request $request)
    {
        $this->setSeo(null, null);

        return $this->render('about.html.twig', [
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
    public function contact(Request $request, MailerInterface $mailer, SpamChecker $spamChecker, string $adminEmail, string $senderEmail)
    {
        $this->setSeo('Contact Us - Macroman Solutions', null);

        $message = new ContactUsMessage();
        $response = new Response();
        $form = $this->createForm(ContactUsType::class, $message);

        if ($request->isMethod('POST')) {
            $form->submit($request->request->get($form->getName()));

            if ($form->isSubmitted() && $form->isValid()) {
                $context = [
                    'user_ip' => $request->getClientIp(),
                    'user_agent' => $request->headers->get('user-agent'),
                    'referrer' => $request->headers->get('referer'),
                    'permalink' => $request->getUri(),
                ];

                $message->setSpamScore($spamChecker->getSpamScore($message, $context));

                if (2 != $message->getSpamScore()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($message);
                    $em->flush();
                }

                if (0 == $message->getSpamScore()) {
                    $mailer->send(
                        (NotificationEmail::asPublicEmail())
                        ->subject($message->getSubject())
                        ->htmlTemplate('@email/default/notification/body.html.twig')
                        ->from($senderEmail)
                        ->to(...\explode(',', $adminEmail))
                        ->context([
                            'content' => $message->getMessage(),
                            'footer_text' => 'Website Contact from: '.$message->getName().' <'.$message->getEmail().'>',
                        ])
                    );

                    $this->addFlash('success', 'Message Submitted Successfully');

                    return $this->redirectToRoute('contact');
                }
                $form->addError(new FormError('Your message has been detected as spam'));
                $response->setStatusCode(422);
            }
        }

        return $this->render('contact.html.twig', [
            'pageTitle' => 'Contact Us',
            'form' => $form->createView(),
        ], $response);
    }


    /**
     * @Route("/web3", name="web3")
     */
    public function web3(Request $request)
    {
        $this->setSeo(null, null);

        return $this->render('web3.html.twig', [
        ]);
    }
}
