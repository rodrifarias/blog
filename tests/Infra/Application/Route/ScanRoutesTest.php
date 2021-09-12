<?php

namespace Tests\Infra\Application\Route;

use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Infra\Application\Route\RouteInfo;
use Rodrifarias\Blog\Infra\Application\Route\ScanRoutes;
use Rodrifarias\Blog\Infra\Exception\DirectoryNotFoundException;

class ScanRoutesTest extends TestCase
{
    private ScanRoutes $scanRoutes;
    private string $dirBase = __DIR__ . '/./RoutesControllers/';

    protected function setUp(): void
    {
        $this->scanRoutes = new ScanRoutes();
    }

    public function testShouldGenerateDirectoryNotFoundExceptionWhenPathNotExists(): void
    {
        $this->expectException(DirectoryNotFoundException::class);
        $this->scanRoutes->getRoutes('/dir-not-exists');
    }

    public function testShouldHave2RoutesMapped(): void
    {
        $routes = $this->scanRoutes->getRoutes($this->dirBase . 'Controller');
        $this->assertCount(3, $routes);
    }

    public function testShouldNotRoutesWhenDirectoryIsEmpty(): void
    {
        $routes = $this->scanRoutes->getRoutes($this->dirBase . 'Empty');
        $this->assertCount(0, $routes);
    }

    public function testShouldNotRoutesWhenDirectoryOnlyHaveClassWithoutAttributes(): void
    {
        $routes = $this->scanRoutes->getRoutes($this->dirBase . 'Test');
        $this->assertCount(0, $routes);
    }

    public function testShouldHave1RouteWithMiddlewares(): void
    {
        $routes = $this->scanRoutes->getRoutes($this->dirBase . 'Controller');
        $filterRoutes = array_filter($routes, fn (RouteInfo $r) => count($r->getMiddleware()) > 0);
        $this->assertCount(1, $filterRoutes);
    }

    public function testShouldHave2RoutesWithPublicAccessTrue(): void
    {
        $routes = $this->scanRoutes->getRoutes($this->dirBase . 'Controller');
        $filterRoutes = array_filter($routes, fn (RouteInfo $r) => $r->isPublicAccess());
        $this->assertCount(2, $filterRoutes);
    }

    public function testShouldHave1RoutesWithPublicAccessFalse(): void
    {
        $routes = $this->scanRoutes->getRoutes($this->dirBase . 'Controller');
        $filterRoutes = array_filter($routes, fn (RouteInfo $r) => !$r->isPublicAccess());
        $this->assertCount(1, $filterRoutes);
    }

    public function testShouldNotScanClassWithIncompleteAttributes(): void
    {
        $routes = $this->scanRoutes->getRoutes($this->dirBase . 'IncompleteController');
        $this->assertCount(0, $routes);
    }
}
