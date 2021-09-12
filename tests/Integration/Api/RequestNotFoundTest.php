<?php

namespace Tests\Integration\Api;

use Fig\Http\Message\StatusCodeInterface;

class RequestNotFoundTest extends TestCaseRequest
{
    public function testShouldReturn404WhenRouteNotFound(): void
    {
        $request = $this->createRequest('route-not-found', 'GET');
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $request->statusCode);
        $this->assertSame('Not found.', $request->content->description);
    }
}
