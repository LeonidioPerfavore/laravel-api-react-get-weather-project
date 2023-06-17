<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Support\Facades\Request;

class CustomMethodNotAllowedHandler extends Handler
{
    /**
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        $others = $e->getHeaders()['Allow'] ?? []; // Use empty array if 'Allow' header is not present
        $method = Request::getMethod();

        abort(403, 'Method not allowed');
    }
}


