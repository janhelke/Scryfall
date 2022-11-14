<?php

namespace Janhelke\Scryfall\Responses;

use GuzzleHttp\Psr7\Response;
use IteratorAggregate;
use Janhelke\Scryfall\ScryfallIterator;
use JsonException;

/**
 * Class Cards
 * https://scryfall.com/docs/api/cards
 */
class Cards extends Base implements IteratorAggregate
{
    /** @var Card[] */
    protected array $cards = [];

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
            $this->setCollectionData(count(@$response->data), @$response->total_cards, @$response->has_more);

            foreach ($response->data as $card) {
                $this->cards[] = new Card($card, false);
            }
        }
    }

    public function getIterator(): ScryfallIterator
    {
        return new ScryfallIterator($this->cards);
    }

    /**
     * @return Card[]
     */
    public function cards(): array
    {
        return $this->cards;
    }
}
