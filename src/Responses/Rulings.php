<?php

namespace Janhelke\Scryfall\Responses;

use GuzzleHttp\Psr7\Response;
use IteratorAggregate;
use Janhelke\Scryfall\ScryfallIterator;
use JsonException;

/**
 * Class Rulings
 * https://scryfall.com/docs/api/rulings
 */
class Rulings extends Base implements IteratorAggregate
{
    /** @var Ruling[] */
    protected array $rulings = [];

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

            foreach ($response->data as $ruling) {
                $this->rulings[] = new Ruling($ruling, false);
            }
        }
    }

    public function getIterator(): ScryfallIterator
    {
        return new ScryfallIterator($this->rulings);
    }

    /**
     * @return Ruling[]
     */
    public function rulings(): array
    {
        return $this->rulings;
    }
}
