<?php

namespace Janhelke\Scryfall\Responses;

use GuzzleHttp\Psr7\Response;
use IteratorAggregate;
use Janhelke\Scryfall\ScryfallIterator;
use JsonException;

/**
 * Class Sets
 * https://scryfall.com/docs/api/sets
 */
class Sets extends Base implements IteratorAggregate
{
    /** @var Set[] */
    protected array $sets = [];

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

            foreach ($response->data as $set) {
                $this->sets[] = new Set($set, false);
            }
        }
    }

    public function getIterator(): ScryfallIterator
    {
        return new ScryfallIterator($this->sets);
    }

    /**
     * @return Set[]
     */
    public function sets(): array
    {
        return $this->sets;
    }
}
