<?php

namespace Janhelke\Scryfall\Responses;

use GuzzleHttp\Psr7\Response;
use JsonException;
use stdClass;

/**
 * Class CardFace
 * https://scryfall.com/docs/api/cards#card-face-objects
 */
class CardFace extends Base
{
    public string $object;

    public string $name;

    public string $manaCost;

    public string $type;

    public string $oracleText;

    public string $flavorText;

    public string $power;

    public string $toughness;

    public string $loyalty;

    /** @var string[] */
    public array $colors = [];

    /** @var string[] */
    public array $colorIndicator = [];

    /** @var string[] */
    public stdClass|array $images = [];

    public string $printedName;

    public string $printedText;

    public string $printedType;

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

        $this->object = $data->object;
        $this->name = @$data->name;
        $this->manaCost = @$data->mana_cost;
        $this->type = @$data->type_line;
        $this->oracleText = @$data->oracle_text;
        $this->flavorText = @$data->flavor_text;
        $this->power = @$data->power;
        $this->toughness= @$data->toughness;
        $this->loyalty = @$data->loyalty;
        $this->colors = @$data->colors;
        $this->colorIndicator = @$data->colorIndicator;
        $this->printedName = @$data->printed_name;
        $this->printedText = @$data->printed_text;
        $this->printedType = @$data->printed_type_line;

        if (property_exists($data, 'image_uris') && $data->image_uris !== null) {
            $images = $data->image_uris;

            $this->images = new stdClass();
            $this->images->small = @$images->small;
            $this->images->normal = @$images->normal;
            $this->images->large = @$images->large;
            $this->images->png = @$images->png;
            $this->images->art_crop = @$images->art_crop;
            $this->images->border_crop = @$images->border_crop;
        }
    }
}
