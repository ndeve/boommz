<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ComicExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('nbSize', [$this, 'nbSize']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function nbSize($value)
    {
        switch ($value) {
            case 'one-quarter':
                return 1/4;
            case "one-third":
                return 1/3;
            case "two-fifths":
                return 2/5;
            case "half":
                return 1/2;
            case "three-fifths":
                return 3/5;
            case "two-thirds":
                return 2/3;
            case "three-quarters":
                return 3/4;
            case "full":
                return 1;
        }
    }
}
