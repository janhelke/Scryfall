<?php

namespace Janhelke\Scryfall\Endpoint;

use Exception;
use Janhelke\Scryfall\Client;
use Janhelke\Scryfall\Exception\ScryfallException;
use Janhelke\Scryfall\Responses\Card;
use Janhelke\Scryfall\Responses\Rulings;

class Cards
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
    public function all(int $page = 1): ?\Janhelke\Scryfall\Responses\Cards
    {
        try {
            $response = $this->client->send('GET', 'cards?page=' . $page);
            return new \Janhelke\Scryfall\Responses\Cards($response);
        } catch (Exception $exception) {
            throw new ScryfallException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param $id
     * @throws ScryfallException
     */
    public function get($id): ?Card
    {
        try {
            $response = $this->client->send('GET', 'cards/' . $id);
            return new Card($response);
        } catch (Exception $exception) {
            throw new ScryfallException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * https://scryfall.com/docs/api/cards/collector
     * GET /cards/:code/:number(/:lang)
     *
     * @throws ScryfallException
     */
    public function getFromSetByNumber(string $code, int $number, string $language = ''): ?Card
    {
        try {
            $response = $this->client->send('GET', 'cards/' . $code . '/' . $number . (empty($language) ? '' : '/' . $language));
            return new Card($response);
        } catch (Exception $exception) {
            throw new ScryfallException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param $query
     * @throws ScryfallException
     */
    public function search(
        $query,
        string $unique = 'cards',
        string $order = 'name',
        string $dir = 'auto',
        bool $extras = false,
        bool $multilang = false,
        int $page = 1
    ): ?\Janhelke\Scryfall\Responses\Cards {
        try {
            // Build string
            $queryString = '?q=' . $query;
            $queryString .= '&unique=' . $unique;
            $queryString .= '&order=' . $order;
            $queryString .= '&dir=' . $dir;
            $queryString .= '&include_extras=' . $extras;
            $queryString .= '&include_multilingual=' . $multilang;
            $queryString .= '&page=' . $page;

            $response = $this->client->send('GET', 'cards/search' . $queryString);
            return new \Janhelke\Scryfall\Responses\Cards($response);
        } catch (Exception $exception) {
            throw new ScryfallException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param $id
     * @param null $type
     * @throws ScryfallException
     */
    public function rulings($id, $type = null): ?Rulings
    {
        $url = 'cards/';

        if (!is_null($type)) {
            $url .= $type . '/';
        }

        try {
            $response = $this->client->send('GET', $url . $id . '/rulings');
            return new Rulings($response);
        } catch (Exception $exception) {
            throw new ScryfallException($exception->getMessage(), $exception->getCode());
        }
    }
}
