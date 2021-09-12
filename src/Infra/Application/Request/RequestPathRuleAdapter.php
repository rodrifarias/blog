<?php

namespace Rodrifarias\Blog\Infra\Application\Request;

use Psr\Http\Message\ServerRequestInterface;
use Tuupola\Middleware\JwtAuthentication\RuleInterface;

final class RequestPathRuleAdapter implements RuleInterface
{
    private array $options = [
        'path' => [],
        'ignore' => []
    ];

    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function __invoke(ServerRequestInterface $request): bool
    {
        $uri = '/' . $request->getUri()->getPath();
        $uri = preg_replace('#/+#', '/', $uri);
        $methodUri = strtolower($request->getMethod());
        $match = fn ($m, $i) => preg_match("@^$i(/.*)?$@", $uri) && $m === $methodUri;

        foreach ($this->options['ignore'] as $ignore) {
            $ignoredPath = rtrim($ignore['path'], '/');
            if ($match($ignore['method'], $ignoredPath)) {
                return false;
            }
        }

        foreach ($this->options['path'] as $path) {
            $protectedPath = rtrim($path['path'], '/');
            if ($match($path['method'], $protectedPath)) {
                return true;
            }
        }

        return false;
    }
}
