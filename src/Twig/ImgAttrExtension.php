<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ImgAttrExtension extends AbstractExtension
{
    private $publicDir;

    public function __construct(ContainerBagInterface $containerBag)
    {
        $this->publicDir = $containerBag->get('kernel.project_dir').'/public';
    }

    public function getFilters(): array
    {
        return [
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('img_attrs', [$this, 'renderImageAttributes'], ['is_safe' => ['html']]),
        ];
    }

    public function renderImageAttributes($url)
    {
        $imgTag = "src=\"$url\"";
        $sizeInfo = \getimagesize($this->publicDir.$url);

        if ($sizeInfo && \is_array($sizeInfo)) {
            if ($sizeInfo[0] > 0 && $sizeInfo[1] > 0) {
                $imgTag .= " width=\"{$sizeInfo[0]}\" height=\"{$sizeInfo[1]}\"";
            }
        }

        return $imgTag;
    }
}
