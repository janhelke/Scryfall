<?php

namespace Tests\Endpoint;

use Exception;
use Janhelke\Scryfall\Exception\ScryfallException;
use Janhelke\Scryfall\Responses\Symbol;
use Tests\TestCase;

class SymbolsTest extends TestCase
{
    /**
     * @throws ScryfallException
     * @throws Exception
     */
    public function testAllSymbols(): void
    {
        $client = $this->getMockedClient('symbols/all.json');
        $symbols = $client->symbols()->all();
        self::assertContainsOnlyInstancesOf(Symbol::class, $symbols->getIterator());
    }

    /**
     * @throws ScryfallException
     */
    public function testParseManacost(): void
    {
        $client = $this->getMockedClient('symbols/parse.json');
        $manacost = $client->symbols()->parse('rgx');
        self::assertEquals('{X}{R}{G}', $manacost->cost);
    }

    /**
     * @throws ScryfallException
     */
    public function testParseWrongManaCost(): void
    {
        $client = $this->getMockedClient('symbols/error_parsing.json', 429);

        $this->expectException(ScryfallException::class);
        $this->expectExceptionCode(429);
        $this->expectExceptionMessage('The string fragment(s) “A” could not be understood as part of mana cost.');

        $client->symbols()->parse('ABC');
    }
}
