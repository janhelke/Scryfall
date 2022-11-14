<?php

namespace Janhelke\Scryfall\Responses;

use GuzzleHttp\Psr7\Response;
use JsonException;

/**
 * Class Ruling
 */
class Ruling extends Base
{
    public string $object;

    public string $source;

    public string $published;

    public string $comment;

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
        $this->source = @$data->source;
        $this->published = @$data->published_at;
        $this->comment = @$data->comment;
    }
}
