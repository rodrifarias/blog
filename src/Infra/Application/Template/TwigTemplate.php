<?php

namespace Rodrifarias\Blog\Infra\Application\Template;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TwigTemplate implements TemplateInterface
{
    public function __construct(private Environment $environment)
    {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $template, array $vars = []): string
    {
        return $this->environment->render($template, $vars);
    }
}
