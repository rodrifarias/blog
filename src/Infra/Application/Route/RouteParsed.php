<?php

namespace Rodrifarias\Blog\Infra\Application\Route;

use FastRoute\RouteParser\Std;

class RouteParsed
{
    /**
     * @param RouteInfo[] $routesInfo
     */
    public static function getRoutesFromListRoutes(array $routesInfo): array
    {
        $routes = [];

        foreach ($routesInfo as $route) {
            $possibleRoutes = self::getRoutesFromRoute($route->getPath());

            foreach ($possibleRoutes as $possibleRoute) {
                $routes[] = [
                    'method' => $route->getHttpMethod(),
                    'path' => $possibleRoute
                ];
            }
        }

        return $routes;
    }

    /**
     * @return string[]
     */
    public static function getRoutesFromRoute(string $patternRoute): array
    {
        $parseUrlStd = new Std();
        $urlParsed = $parseUrlStd->parse($patternRoute);
        $possibleRoutes = array_map(fn ($url) => self::getPossibleRoutes($url), $urlParsed);

        return array_reverse($possibleRoutes);
    }

    private static function getPossibleRoutes(array $route): string
    {
        $routeStr = $route[0];

        foreach ($route as $key => $item) {
            if ($key > 0) {
                $routeStr .= is_array($item) ? $item[1] : $item;
            }
        }

        return $routeStr;
    }
}
