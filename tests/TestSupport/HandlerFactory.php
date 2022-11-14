<?php

namespace Tests\TestSupport;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class HandlerFactory
{
    public static function getHandler(string $file, int $statusCode = 200): HandlerStack
    {
        return self::getHandlerForResponse(self::getResponse($file, $statusCode));
    }

    protected static function getHandlerForResponse($response): HandlerStack
    {
        $mock = new MockHandler([
            $response,
        ]);

        return HandlerStack::create($mock);
    }

    protected static function getResponse(string $file, int $statusCode = 200): Response
    {
        return new Response($statusCode, ['Content-Type' => 'application/json'], self::getContents('/responses/' . $file));
    }

    protected static function getContents(string $filename): string|bool
    {
        return file_get_contents(__DIR__ . $filename);
    }
}
