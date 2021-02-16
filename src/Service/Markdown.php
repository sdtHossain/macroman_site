<?php

namespace App\Service;

use App\Entity\JobPost;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class Markdown
{
    const FrontMatterFields = [
        'title',
        'jobType',
        'jobCategory',
        'vacancy',
        'publishedAt',
        'openUntil',
        'open',
        'applyUrl',
    ];

    /** @var MarkdownConverter */
    private $converter;

    /** @var FrontMatterExtension */
    private $frontMatterExtension;

    /** @var PropertyAccessorInterface */
    private $propertyAccess;

    public function __construct(PropertyAccessorInterface $propertyAccess)
    {
        $this->propertyAccess = $propertyAccess;

        $environment = Environment::createCommonMarkEnvironment();
        $this->frontMatterExtension = new FrontMatterExtension(new FrontMatterParser());

        $environment->addExtension(new AutolinkExtension());
        $environment->addExtension(new StrikethroughExtension());
        $environment->addExtension(new TableExtension());
        $environment->addExtension(new TaskListExtension());
        $environment->addExtension(new AttributesExtension());
        $environment->addExtension($this->frontMatterExtension);

        $this->converter = new MarkdownConverter($environment);
    }

    public function parseFile($markdown, $frontMatterOnly = false): ?JobPost
    {
        $result = $this->frontMatterExtension->getFrontMatterParser()->parse($markdown);

        $jobPost = new JobPost();

        $fields = $result->getFrontMatter();

        foreach (static::FrontMatterFields as $fieldName) {
            if (isset($fields[$fieldName])) {
                $this->propertyAccess->setValue($jobPost, $fieldName, $fields[$fieldName]);
            }
        }

        if (!$frontMatterOnly) {
            $jobPost->setContent($this->converter->convertToHtml($result->getContent()));
        }

        return $jobPost;
    }
}
