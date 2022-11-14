<?php

namespace Janhelke\Scryfall\Responses;

use GuzzleHttp\Psr7\Response;
use Janhelke\Scryfall\Client;
use Janhelke\Scryfall\Exception\ScryfallException;
use JsonException;

/**
 * Class Set
 */
class Set extends Base
{
    public string $object;

    public ?string $parent;

    public string $code;

    public ?string $mtgoCode;

    public string $name;

    public ?string $release;

    public string $setType;

    public ?string $block;

    public ?string $blockCode;

    public int $cardCount;

    public bool $digitalOnly;

    public bool $foilOnly;

    /**
     * Set constructor.
     * @param $data
     * @throws JsonException
     */
    public function __construct($data, bool $initialize = true)
    {
        if ($data instanceof Response) {
            parent::__construct($data, $initialize);
            $data = json_decode($data->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        } else {
            parent::__construct(null, false);
        }

        $this->object = @$data->object;
        $this->parent = @$data->parent_set_code;
        $this->code = @$data->code;
        $this->mtgoCode = @$data->mtgo_code;
        $this->name = @$data->name;
        $this->release = @$data->released_at;
        $this->setType = @$data->set_type;
        $this->block = @$data->block;
        $this->blockCode = @$data->block_code;
        $this->cardCount = @$data->card_count;
        $this->digitalOnly = @$data->digital;
        $this->foilOnly = @$data->foil_only;
    }

    /**
     * @throws ScryfallException
     */
    public function getCards(Client $client): ?Cards
    {
        return $client->cards()->search('set:' . $this->code, 'prints', 'set');
    }
}
