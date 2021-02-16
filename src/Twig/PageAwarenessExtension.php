<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PageAwarenessExtension extends AbstractExtension
{
    /** @var Request */
    protected $req;

    public function __construct(RequestStack $requestStack)
    {
        $this->req = $requestStack->getCurrentRequest();
    }

    public function getFilters(): array
    {
        return [
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_page', [$this, 'isPage']),
        ];
    }

    public function isPage($name, $matchParent = false)
    {
        if ($matchParent) {
            return \str_starts_with($this->req->attributes->get('_route'), $name);
        }

        return $this->req->attributes->get('_route') == $name;
    }
}
