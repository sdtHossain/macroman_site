<?php

namespace App\Service;

use App\Entity\ContactUsMessage;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpamChecker
{
    private $client;
    private $endpoint;
    private $isTest;

    public function __construct(HttpClientInterface $client, string $akismetKey, $kernelEnv)
    {
        $this->client = $client;
        $this->endpoint = \sprintf('https://%s.rest.akismet.com/1.1/comment-check', $akismetKey);
        $this->isTest = 'test' == $kernelEnv;
    }

    /**
     * @return int Spam score: 0: not spam, 1: maybe spam, 2: blatant spam
     *
     * @throws \RuntimeException if the call did not work
     */
    public function getSpamScore(ContactUsMessage $message, array $context): int
    {
        $response = $this->client->request('POST', $this->endpoint, [
            'body' => \array_merge($context, [
                'blog' => 'https://macromanhq.com/',
                'comment_type' => 'contact-form',
                'comment_author' => $message->getName(),
                'comment_author_email' => $message->getEmail(),
                'comment_content' => $message->getMessage(),
                'comment_date_gmt' => $message->getCreatedAt()->format('c'),
                'blog_lang' => 'en',
                'blog_charset' => 'UTF-8',
                'is_test' => $this->isTest,
            ]),
        ]);

        $headers = $response->getHeaders();
        if ('discard' === ($headers['x-akismet-pro-tip'][0] ?? '')) {
            return 2;
        }

        $content = $response->getContent();
        if (isset($headers['x-akismet-debug-help'][0])) {
            throw new \RuntimeException(\sprintf('Unable to check for spam: %s (%s).', $content, $headers['x-akismet-debug-help'][0]));
        }

        return 'true' === $content ? 1 : 0;
    }
}
