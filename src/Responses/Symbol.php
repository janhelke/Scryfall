<?php

namespace Janhelke\Scryfall\Responses;

use GuzzleHttp\Psr7\Response;
use JsonException;

/**
 * Class Symbol
 */
class Symbol extends Base
{
    public string $object;

    public string $symbol;

    public string $looseSymbol;

    public string $description;

    public bool $transposable;

    public string $representsMana;

    public string $appearsInManacost;

    public int $cmc;

    public bool $isFunny;

    public array $colors = [];

    public array $alternates = [];

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
        $this->symbol = @$data->symbol;
        $this->looseSymbol = @$data->loose_variant;
        $this->description = @$data->english;
        $this->transposable = @$data->transposable;
        $this->representsMana = @$data->represents_mana;
        $this->appearsInManacost = @$data->appears_in_mana_costs;
        $this->cmc = @$data->cmc;
        $this->isFunny = @$data->funny;
        $this->colors = @$data->colors;
        $this->alternates = @$data->gatherer_alternates;
    }
}
