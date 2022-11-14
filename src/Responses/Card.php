<?php

namespace Janhelke\Scryfall\Responses;

use GuzzleHttp\Psr7\Response;
use Janhelke\Scryfall\ScryfallIterator;
use JsonException;
use stdClass;

/**
 * Class Card
 */
class Card extends Base
{
    public string $object;

    public string $idScryfall;

    public string $idOracle;

    public ?int $idMtgo;

    public ?int $idMtgoFoil;

    public ?int $idArena;

    /** @var int[]|null */
    public ?array $idsMultiverse = [];

    public string $language;

    public string $name;

    public ?string $printedName;

    public string $type;

    public ?string $printedType;

    public ?string $oracleText;

    public ?string $printedText;

    public ?string $flavorText;

    public string $manaCost;

    public ?float $cmc;

    /** @var string[]|null */
    public ?array $colors = [];

    /** @var string[] */
    public array $colorIdentity = [];

    /** @var string[]|null */
    public ?array $colorIndicator = [];

    public string $number;

    public string $rarity;

    public ?string $power;

    public ?string $toughness;

    public ?string $loyalty;

    /** @var CardFace[]|null */
    public ?array $cardFaces = [];

    public string $set;

    public string $setName;

    public string $layout;

    public string $frame;

    public string $borderColor;

    public ?string $watermark;

    public ?string $artist;

    public ?string $handModifier;

    public ?string $lifeModifier;

    public bool $isReserved;

    public bool $isFoil;

    public bool $isNonFoil;

    public bool $isReprint;

    public bool $isOversized;

    public bool $isDigital;

    public bool $isFullart;

    public ?bool $isTimeshifted;

    public ?bool $isColorshifted;

    public ?bool $isFutureshifted;

    public stdClass $legalities;

    public stdClass $images;

    public stdClass $prices;

    /** @var stdClass[] */
    public array $related = [];

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

        $this->idScryfall = @$data->id;
        $this->idOracle = @$data->oracle_id;
        $this->idMtgo = @$data->mtgo_id;
        $this->idMtgoFoil = @$data->mtgo_foil_id;
        $this->idArena = @$data->arena_id;
        $this->idsMultiverse = @$data->multiverse_ids;

        $this->language = @$data->lang;
        $this->name = @$data->name;
        $this->printedName = @$data->printed_name;
        $this->type = @$data->type_line;
        $this->printedType = @$data->printed_type_line;
        $this->oracleText = @$data->oracle_text;
        $this->printedText = @$data->printed_text;
        $this->flavorText = @$data->flavor_text;
        $this->manaCost = @$data->mana_cost;
        $this->cmc = @$data->cmc;
        $this->colors = @$data->colors;
        $this->colorIdentity = @$data->color_identity;
        $this->colorIndicator = @$data->color_indicator;
        $this->number = @$data->collector_number;
        $this->rarity = @$data->rarity;
        $this->power = @$data->power;
        $this->toughness = @$data->tougness;
        $this->loyalty = @$data->loyalty;

        $this->set = @$data->set;
        $this->setName = @$data->set_name;

        $this->layout = @$data->layout;
        $this->frame = @$data->frame;
        $this->borderColor = @$data->border_color;
        $this->watermark = @$data->watermark;
        $this->artist = @$data->artist;

        $this->handModifier = @$data->hand_modifier;
        $this->lifeModifier = @$data->life_modifier;

        $this->isReserved = @$data->reserved;
        $this->isFoil = @$data->foil;
        $this->isNonFoil = @$data->nonfoil;
        $this->isReprint = @$data->reprint;
        $this->isOversized = @$data->oversized;
        $this->isDigital = @$data->digital;
        $this->isFullart = @$data->full_art;
        $this->isTimeshifted = @$data->timeshifted;
        $this->isColorshifted = @$data->colorshifted;
        $this->isFutureshifted = @$data->futureshifted;

        if (property_exists($data, 'card_faces') && $data->card_faces !== null) {
            foreach ($data->card_faces as $face) {
                $this->cardFaces[] = new CardFace($face, false);
            }
        }

        if (property_exists($data, 'legalities') && $data->legalities !== null) {
            $legalities = $data->legalities;

            $this->legalities = new stdClass();
            $this->legalities->standard = $this->isLegal(@$legalities->standard);
            $this->legalities->modern = $this->isLegal(@$legalities->modern);
            $this->legalities->legacy = $this->isLegal(@$legalities->legacy);
            $this->legalities->vintage = $this->isLegal(@$legalities->vintage);
            $this->legalities->commander = $this->isLegal(@$legalities->commander);
            $this->legalities->pauper = $this->isLegal(@$legalities->pauper);
            $this->legalities->frontier = $this->isLegal(@$legalities->frontier);
            $this->legalities->brawl = $this->isLegal(@$legalities->brawl);
            $this->legalities->penny = $this->isLegal(@$legalities->penny);
            $this->legalities->future = $this->isLegal(@$legalities->future);
            $this->legalities->duel = $this->isLegal(@$legalities->duel);
            $this->legalities->{'1v1'} = $this->isLegal(@$legalities->{'1v1'});
        }

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

        if ((property_exists($data, 'usd') && $data->usd !== null) || (property_exists(
            $data,
            'eur'
        ) && $data->eur !== null) || (property_exists($data, 'tix') && $data->tix !== null)) {
            $this->prices = new stdClass();
            $this->prices->usd = @$data->usd;
            $this->prices->eur = @$data->eur;
            $this->prices->tix = @$data->tix;
        }

        if (property_exists($data, 'all_parts') && $data->all_parts !== null) {
            foreach ($data->all_parts as $part) {
                $related = new stdClass();
                $related->object = @$part->object;
                $related->id = @$part->id;
                $related->name = @$part->name;

                $this->related[] = $related;
            }
        }
    }

    public function getFaces(): ScryfallIterator
    {
        return new ScryfallIterator($this->cardFaces);
    }

    public function getRelated(): ScryfallIterator
    {
        return new ScryfallIterator($this->related);
    }

    /**
     * @param $value
     */
    private function isLegal($value): bool
    {
        return $value === 'legal';
    }
}
