<?php

namespace Rodrifarias\Blog\Infra\Application\Route;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Rodrifarias\Blog\Infra\Exception\DirectoryNotFoundException;

class ScanRoutes
{
    /**
     * @throws DirectoryNotFoundException
     * @throws ReflectionException
     * @return RouteInfo[]
     */
    public function getRoutes(string $path): array
    {
        if (!is_dir($path)) {
            throw new DirectoryNotFoundException();
        }

        $files = $this->getFilesScan($path);
        return $this->scanClassFiles($files);
    }

    /**
     * @return string[]
     */
    private function getFilesScan(string $path): array
    {
        $files = [];
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $it->rewind();

        while ($it->valid()) {
            if (!$it->isDot() && file_exists($it->key())) {
                $filename = pathinfo($it->getSubPathName(), PATHINFO_FILENAME);
                $files[] = $this->extractNamespaceFile($it->key()) . '\\' . $filename;
            }

            $it->next();
        }

        return $files;
    }

    private function extractNamespaceFile(string $file): string
    {
        $ns = '';
        $handler = fopen($file, "r");

        if ($handler) {
            while (($line = fgets($handler)) !== false) {
                if (str_starts_with($line, 'namespace')) {
                    $parts = explode(' ', $line);
                    $ns = rtrim(trim($parts[1]), ';');
                    break;
                }
            }
            fclose($handler);
        }

        return $ns;
    }

    /**
     * @throws ReflectionException
     */
    private function scanClassFiles(array $class): array
    {
        $routes = [];

        foreach ($class as $cla) {
            $reflectionClass = new ReflectionClass($cla);

            if (!$reflectionClass->getAttributes()) {
                continue;
            }

            $prefix = $this->prefixRoute($reflectionClass);

            if (!$prefix) {
                continue;
            }

            foreach ($reflectionClass->getMethods() as $method) {
                if ($method->getAttributes()) {
                    $infoRoute = $this->attributesMethodRoute($method);
                    $routes[] = new RouteInfo(
                        $reflectionClass->getName(),
                        $infoRoute['classMethod'],
                        $infoRoute['httpMethod'],
                        $prefix . $infoRoute['path'],
                        $infoRoute['publicAccess'],
                        $infoRoute['middleware'],
                    );
                }
            }
        }

        return $routes;
    }

    private function attributesMethodRoute(ReflectionMethod $reflectionMethod): array
    {
        $isPublicAccess = fn (string $value) => str_ends_with($value, 'PublicAccess');
        $isMiddleware = fn (string $value) => str_ends_with($value, 'Middleware');

        $isMethodHttp = function (string $classNamespace): bool {
            $namespaceArray = explode('\\', $classNamespace);
            $method = array_pop($namespaceArray);
            return in_array($method, ['Get', 'Post', 'Put', 'Delete', 'Patch']);
        };

        $getNameMethodHttp = function (string $classNamespace): string {
            $namespaceArray = explode('\\', $classNamespace);
            return strtolower(array_pop($namespaceArray));
        };

        $infoRoute = [
            'middleware' => [],
            'publicAccess' => false,
            'classMethod' => $reflectionMethod->getName(),
        ];

        foreach ($reflectionMethod->getAttributes() as $attribute) {
            $arguments = $attribute->getArguments();

            if ($isMethodHttp($attribute->getName())) {
                $infoRoute['httpMethod'] = $getNameMethodHttp($attribute->getName());
                $infoRoute['path'] = $arguments ? $arguments[0] : '';
            }

            if ($isPublicAccess($attribute->getName())) {
                $infoRoute['publicAccess'] = $arguments ? $arguments[0] : '';
            }

            if ($isMiddleware($attribute->getName())) {
                $infoRoute['middleware'] = $arguments[0];
            }
        }

        return $infoRoute;
    }

    private function prefixRoute(ReflectionClass $reflectionClass): ?string
    {
        $attributes = $reflectionClass->getAttributes();

        foreach ($attributes as $attribute) {
            $className = $attribute->getName();
            $classNameIsRoute = str_ends_with($className, 'Route');

            if ($classNameIsRoute && $attribute->getArguments()) {
                return $attribute->getArguments()[0];
            }
        }

        return null;
    }
}
