<?php

namespace Tests\Endpoint;

use Exception;
use Janhelke\Scryfall\Exception\ScryfallException;
use Janhelke\Scryfall\Responses\Set;
use Tests\TestCase;

class SetsTest extends TestCase
{
    /**
     * @throws ScryfallException
     * @throws Exception
     */
    public function testGetAllSets(): void
    {
        $client = $this->getMockedClient('sets/all.json');
        $sets = $client->sets()->all();
        self::assertContainsOnlyInstancesOf(Set::class, $sets->getIterator());
    }

    /**
     * @throws ScryfallException
     */
    public function testGetInvalidSet(): void
    {
        $client = $this->getMockedClient('sets/set_not_found.json', 404);

        $this->expectException(ScryfallException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('No set found for the given code.');

        $client->sets()->get('wrong_code');
    }
}
