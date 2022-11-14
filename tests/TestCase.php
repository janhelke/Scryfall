<?php

namespace Tests;

use Janhelke\Scryfall\Client;
use Tests\TestSupport\HandlerFactory;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function getMockedClient(string $method, int $statusCode = 200): Client
    {
        $handler = HandlerFactory::getHandler($method, $statusCode);

        $httpClient = new \GuzzleHttp\Client([
            'handler' => $handler,
            'base_uri' => 'https://api.scryfall.com/',
        ]);

        return new Client($httpClient);
    }
}
