<?php

namespace Janhelke\Scryfall\Responses;

use GuzzleHttp\Psr7\Response;
use JsonException;

/**
 * Class Manacost
 * https://scryfall.com/docs/api/card-symbols/parse-mana
 */
class ParsedMana extends Base
{
    public string $object;

    public string $cost;

    public array $colors = [];

    public int $cmc;

    public bool $isColorless;

    public bool $isMonoColor;

    public bool $isMultiColor;

    /**
     * Set constructor.
     * @param $data
     * @throws JsonException
     */
    public function __construct($data)
    {
        if ($data instanceof Response) {
            parent::__construct($data);
            $data = json_decode($data->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        } else {
            parent::__construct(null, false);
        }

        $this->object = @$data->object;
        $this->cost = @$data->cost;
        $this->colors = @$data->colors;
        $this->cmc = @$data->cmc;
        $this->isColorless = @$data->colorless;
        $this->isMonoColor = @$data->monocolored;
        $this->isMultiColor = @$data->multicolored;
    }
}
