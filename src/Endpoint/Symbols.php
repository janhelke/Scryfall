<?php

namespace Janhelke\Scryfall\Endpoint;

use Exception;
use Janhelke\Scryfall\Client;
use Janhelke\Scryfall\Exception\ScryfallException;
use Janhelke\Scryfall\Responses\ParsedMana;

class Symbols
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
    public function all(): \Janhelke\Scryfall\Responses\Symbols
    {
        try {
            $response = $this->client->send('GET', 'symbology');
            return new \Janhelke\Scryfall\Responses\Symbols($response);
        } catch (Exception $exception) {
            throw new ScryfallException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param $cost
     * @throws ScryfallException
     */
    public function parse($cost): ParsedMana
    {
        try {
            $response = $this->client->send('GET', 'symbology/parse-mana?cost=' . $cost);
            return new ParsedMana($response);
        } catch (Exception $exception) {
            throw new ScryfallException($exception->getMessage(), $exception->getCode());
        }
    }
}
