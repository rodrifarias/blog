<?php

namespace Tests\Integration\Api\Swagger;

use Fig\Http\Message\StatusCodeInterface;
use Tests\Integration\Api\TestCaseRequest;

class ShowSwaggerPageTest extends TestCaseRequest
{
    public function testShouldShowSwaggerPage(): void
    {
        $requestShowSwaggerUi = $this->createRequest('swagger/ui', 'GET');
        $contentType = $requestShowSwaggerUi->header['Content-type'];

        $this->assertSame(StatusCodeInterface::STATUS_OK, $requestShowSwaggerUi->statusCode);
        $this->assertArrayHasKey('Content-type', $requestShowSwaggerUi->header);
        $this->assertTrue(str_contains($contentType[0], 'text/html'));
    }

    public function testShouldShowSwaggerConfigJson(): void
    {
        $requestShowSwaggerConfig = $this->createRequest('swagger/config', 'GET');

        $this->assertSame(StatusCodeInterface::STATUS_OK, $requestShowSwaggerConfig->statusCode);
        $this->assertObjectHasAttribute('openapi', $requestShowSwaggerConfig->content);
        $this->assertObjectHasAttribute('info', $requestShowSwaggerConfig->content);
    }
}