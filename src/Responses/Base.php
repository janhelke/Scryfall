<?php

namespace Janhelke\Scryfall\Responses;

use GuzzleHttp\Psr7\Response;

class Base
{
    protected int $statusCode;

    protected string $message;

    protected bool $hasError = false;

    protected string $errorMessage;

    private ?int $count = null;

    private ?int $total = null;

    private bool $hasMore = false;

    /**
     * Base constructor.
     * @param Response|null $data
     */
    public function __construct(Response $data = null, bool $initialize = true)
    {
        if ($initialize) {
            $this->statusCode = @$data->getStatusCode();
            $this->message = @$data->getReasonPhrase();

            if ($this->statusCode >= 400) {
                $this->hasError = true;
            } else {
                unset($this->errorMessage);
            }
        } else {
            unset($this->statusCode, $this->message, $this->hasError, $this->errorMessage);
        }
    }

    public function setStatusCode(int $statusCode): Base
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    protected function setCollectionData(int $count, int $total = 0, bool $hasMore = false): void
    {
        $this->count = $count;
        $this->total = $total;

        $this->hasMore = $hasMore && $this->total > 0 && $this->count > 0;
    }

    public function count(): ?int
    {
        return $this->count;
    }

    public function total(): ?int
    {
        return $this->total;
    }

    public function hasMore(): bool
    {
        return $this->hasMore;
    }
}
