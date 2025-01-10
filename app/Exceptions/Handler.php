<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException) {
            // Return the response with correct type
            return new Response(
                json_encode([
                    'message' => 'Too Many Attempts. Please try again later.',
                    'status' => 429,
                ]),
                429,
                ['Content-Type' => 'application/json']
            );
        }

        return parent::render($request, $exception);
    }
}
