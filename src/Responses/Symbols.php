<?php

namespace Janhelke\Scryfall\Responses;

use GuzzleHttp\Psr7\Response;
use IteratorAggregate;
use Janhelke\Scryfall\ScryfallIterator;
use JsonException;

/**
 * Class Sets
 * https://scryfall.com/docs/api/card-symbols/all
 */
class Symbols extends Base implements IteratorAggregate
{
    /** @var Symbol[] */
    protected array $symbols = [];

    /**
     * Expansions constructor.
     * @throws JsonException
     */
    public function __construct(Response $data)
    {
        parent::__construct($data);

        $response = json_decode($data->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        if (!$this->hasError) {
            // Set some collection data
            $this->setCollectionData(count(@$response->data), count(@$response->data), @$response->has_more);

            foreach ($response->data as $symbol) {
                $this->symbols[] = new Symbol($symbol, false);
            }
        }
    }

    public function getIterator(): ScryfallIterator
    {
        return new ScryfallIterator($this->symbols);
    }

    /**
     * @return Symbol[]
     */
    public function symbols(): array
    {
        return $this->symbols;
    }
}
