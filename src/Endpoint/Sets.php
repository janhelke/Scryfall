<?php

namespace Janhelke\Scryfall\Endpoint;

use Exception;
use Janhelke\Scryfall\Client;
use Janhelke\Scryfall\Exception\ScryfallException;
use Janhelke\Scryfall\Responses\Set;

class Sets
{
    protected Client $client;

    /**
     * Account constructor.
     * @param $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @throws ScryfallException
     */
    public function all(): \Janhelke\Scryfall\Responses\Sets
    {
        try {
            $response = $this->client->send('GET', 'sets');
            return new \Janhelke\Scryfall\Responses\Sets($response);
        } catch (Exception $exception) {
            throw new ScryfallException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param $code
     * @throws ScryfallException
     */
    public function get($code): Set
    {
        try {
            $response = $this->client->send('GET', 'sets/' . $code);
            return new Set($response);
        } catch (Exception $exception) {
            throw new ScryfallException($exception->getMessage(), $exception->getCode());
        }
    }
}
