<?php

namespace Tests\Endpoint;

use Exception;
use Janhelke\Scryfall\Exception\ScryfallException;
use Janhelke\Scryfall\Responses\Card;
use Janhelke\Scryfall\Responses\CardFace;
use Janhelke\Scryfall\Responses\Cards;
use stdClass;
use Tests\TestCase;

class CardsTest extends TestCase
{
    /**
     * @throws ScryfallException
     * @throws Exception
     */
    public function testAllCards(): void
    {
        $client = $this->getMockedClient('cards/all.json');
        $cards = $client->cards()->all();

        self::assertInstanceOf(Cards::class, $cards);
        self::assertContainsOnlyInstancesOf(Card::class, $cards->getIterator());
    }

    /**
     * @throws ScryfallException
     */
    public function testGetSaheeliTheGifted(): void
    {
        $client = $this->getMockedClient('cards/saheeli_the_gifted.json');
        $card = $client->cards()->get('b32b3dae-7616-46ad-b9bf-854559cda977');

        self::assertEquals('normal', $card->layout);
        self::assertEquals('4', $card->loyalty);
        self::assertTrue($card->isOversized);
        self::assertFalse($card->isNonFoil);
        self::assertInstanceOf(Card::class, $card);
    }

    /**
     * @throws ScryfallException
     */
    public function testGetDelverOfSecrets(): void
    {
        $client = $this->getMockedClient('cards/delver_of_secrets.json');
        $card = $client->cards()->get('11bf83bb-c95b-4b4f-9a56-ce7a1816307a');

        self::assertEquals('transform', $card->layout);
        self::assertInstanceOf(Card::class, $card);
        self::assertContainsOnlyInstancesOf(CardFace::class, $card->getFaces());
    }

    /**
     * @throws ScryfallException
     */
    public function testGetGrafRats(): void
    {
        $client = $this->getMockedClient('cards/graf_rats.json');
        $card = $client->cards()->get('3dedaff6-bd69-4fe3-a301-f7ea7c2f2861');

        self::assertEquals('meld', $card->layout);
        self::assertInstanceOf(Card::class, $card);
        self::assertContainsOnlyInstancesOf(stdClass::class, $card->getRelated());
    }

    /**
     * @throws ScryfallException
     * @throws Exception
     */
    public function testSearchDefault(): void
    {
        $client = $this->getMockedClient('cards/search_default.json');
        $cards = $client->cards()->search('pacifism');

        self::assertInstanceOf(Cards::class, $cards);
        $this->assertequals(1, $cards->total());
        self::assertContainsOnlyInstancesOf(Card::class, $cards->getIterator());
    }

    /**
     * @throws ScryfallException
     * @throws Exception
     */
    public function testSearchUniqueArts(): void
    {
        $client = $this->getMockedClient('cards/search_unique_art.json');
        $cards = $client->cards()->search('lightning helix', 'art');

        self::assertInstanceOf(Cards::class, $cards);
        $this->assertequals(3, $cards->total());
        self::assertContainsOnlyInstancesOf(Card::class, $cards->getIterator());
    }

    /**
     * @throws ScryfallException
     */
    public function testSearchNoResults(): void
    {
        $client = $this->getMockedClient('cards/search_no_results.json', 404);

        $this->expectException(ScryfallException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('Your query didn’t match any cards. Adjust your search terms or refer to the syntax guide at https://scryfall.com/docs/reference');

        $client->cards()->search('non existing card');
    }
}
