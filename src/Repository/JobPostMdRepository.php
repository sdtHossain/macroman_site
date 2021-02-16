<?php

namespace App\Repository;

use App\Entity\JobPost;
use App\Service\Markdown;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Finder\Finder;

class JobPostMdRepository
{
    /** @var Markdown */
    private $markdown;

    private $postDir;

    public function __construct(ContainerBagInterface $containerBag, Markdown $markdown)
    {
        $this->postDir = $containerBag->get('kernel.project_dir').'/src/Post';
        $this->markdown = $markdown;
    }

    public function findOneBySlug($slug): ?JobPost
    {
        $jobs = $this->findBy();

        return $jobs[$slug];
    }

    /**
     * @return JobPost[]
     */
    public function findBy(): array
    {
        $result = [];

        $finder = new Finder();
        $finder->files()->name(['*.md', '*.markdown'])->in($this->postDir);

        foreach ($finder as $file) {
            $slug = $file->getFilenameWithoutExtension();
            $contents = $file->getContents();
            $jobPost = $this->markdown->parseFile($contents);
            $jobPost->setSlug($slug);
            $result[$slug] = $jobPost;
        }

        return $result;
    }
}
