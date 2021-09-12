<?php

namespace Rodrifarias\Blog\Infra\Application\Template;

interface TemplateInterface
{
    public function render(string $template, array $vars = []): string;
}
