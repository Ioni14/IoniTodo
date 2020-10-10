<?php

namespace App\Tests\Domain\ItemList;

use Domain\ItemList\ItemList;
use Domain\ItemList\ItemListId;
use Domain\ItemList\ListCreatedEvent;
use Infra\Common\RamseyUuidFactory;
use Infra\Common\SystemClock;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ItemListTest extends TestCase
{
    /**
     * @test
     * @group toto
     */
    public function it_should_create(): void
    {
        $id = ItemListId::fromString('e4d4406c-58da-470c-b3de-436ee877421b');
        $createdAt = (new SystemClock())->currentDateTime();

        $itemList = ItemList::create(
            $id,
            'My item list',
            $createdAt,
        );
        $events = $itemList->releaseEvents();

        static::assertCount(1, $events);
        /** @var ListCreatedEvent $event */
        $event = $events[0];
        static::assertInstanceOf(ListCreatedEvent::class, $event);
        static::assertEquals($id, $event->getId());
        static::assertSame('My item list', $event->getName());
    }
}
