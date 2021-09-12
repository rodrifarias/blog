<?php

namespace Rodrifarias\Blog\Infra\Application\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\ResponseEmitter;

class ShutdownHandler
{
    public function __construct(
        private ServerRequestInterface $request,
        private HttpErrorHandler $errorHandler,
        private bool $displayErrors
    ) {
    }

    public function __invoke()
    {
        $error = error_get_last();

        if ($error) {
            $errorFile = $error['file'];
            $errorLine = $error['line'];
            $errorMessage = $error['message'];
            $errorType = $error['type'];
            $message = 'An error while processing your request. Please try again later.';

            if ($this->displayErrors) {
                $message = match($errorType) {
                    E_USER_ERROR => "FATAL ERROR: $errorMessage. on line $errorLine in file $errorFile.",
                    E_USER_WARNING => "NOTICE: $errorMessage",
                    E_USER_NOTICE => "WARNING: $errorMessage",
                    default => "ERROR: $errorMessage on line $errorLine in file $errorFile."
                };
            }

            $exception = new HttpInternalServerErrorException($this->request, $message);
            $response = $this->errorHandler->__invoke($this->request, $exception, $this->displayErrors,false,false);

            if (ob_get_length()) {
                ob_clean();
            }

            $responseEmitter = new ResponseEmitter();
            $responseEmitter->emit($response);
        }
    }
}
