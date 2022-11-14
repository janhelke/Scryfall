<?php

namespace Janhelke\Scryfall;

use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Janhelke\Scryfall\Endpoint\Cards;
use Janhelke\Scryfall\Endpoint\Sets;
use Janhelke\Scryfall\Endpoint\Symbols;
use Janhelke\Scryfall\Exception\ScryfallException;
use JsonException;

class Client
{
    protected \GuzzleHttp\Client $guzzle;

    protected string $baseURI = 'https://api.scryfall.com/';

    /**
     * Client constructor.
     * @param null $httpClient
     */
    public function __construct($httpClient = null)
    {
        $this->guzzle = is_null($httpClient) ? new \GuzzleHttp\Client([
            'base_uri' => $this->baseURI,
        ]) : $httpClient;
    }

    public function sets(): Sets
    {
        return new Sets($this);
    }

    public function cards(): Cards
    {
        return new Cards($this);
    }

    public function symbols(): Symbols
    {
        return new Symbols($this);
    }

    /**
     * @param $method
     * @param $url
     * @param null $parameters
     * @throws JsonException
     * @throws ScryfallException
     */
    public function send($method, $url, $parameters = null): ?Response
    {
        try {
            // If we have GET or PUT, and we have parameters, add them to the URL
            if (is_array($parameters) && in_array($method, ['GET', 'PUT'])) {
                $url .= $this->generateParameterString($parameters);
            }

            /** @var Response $response */
            $response = $this->guzzle->request($method, $this->baseURI . $url, [
                'body' => ($method === 'POST' ? $this->getXml($parameters) : ''),
            ]);

            return $response;
        } catch (ClientException $clientException) {
            $json = json_decode(
                $clientException->getResponse()->getBody()->getContents(),
                false,
                512,
                JSON_THROW_ON_ERROR
            );
            throw new ScryfallException($json->details, $clientException->getCode());
        } catch (GuzzleException $guzzleException) {
            throw new ScryfallException($guzzleException->getMessage(), $guzzleException->getCode());
        } catch (Exception $exception) {
            throw new ScryfallException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Converts an array of parameters to a valid string
     */
    protected function generateParameterString(array $parameters): string
    {
        // Start string with ?
        $string = '?';

        foreach ($parameters as $k => $v) {
            if (is_bool($v)) {
                $string .= $k . '=' . ($v ? 'true' : 'false') . '&';
            } else {
                $string .= $k . '=' . $v . '&';
            }
        }

        // Remove last &
        return substr($string, 0, -1);
    }
}
