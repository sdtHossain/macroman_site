<?php

namespace App\Service;

use League\CommonMark\Extension\FrontMatter\Data\FrontMatterDataParserInterface;
use League\CommonMark\Extension\FrontMatter\Exception\InvalidFrontMatterException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class FrontMatterParser implements FrontMatterDataParserInterface
{
    public function parse(string $frontMatter)
    {
        if (!\class_exists(Yaml::class)) {
            throw new \RuntimeException('Failed to parse yaml: "symfony/yaml" library is missing');
        }

        try {
            return Yaml::parse($frontMatter, Yaml::PARSE_DATETIME);
        } catch (ParseException $ex) {
            throw InvalidFrontMatterException::wrap($ex);
        }
    }
}
