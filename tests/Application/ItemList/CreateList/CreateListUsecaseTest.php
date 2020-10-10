<?php

namespace App\Tests\Application\ItemList\CreateList;

use Application\ItemList\CreateList\CreateList;
use Application\ItemList\CreateList\CreateListUsecase;
use Domain\ItemList\ListCreatedEvent;
use Infra\Common\RamseyUuidFactory;
use Infra\ItemList\Repository\ItemListInMemoryRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class CreateListUsecaseTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_persist_and_dispatch(): void
    {
        $repo = new ItemListInMemoryRepository();
        $fakeMiddleware = new class implements MiddlewareInterface {
            public array $envelopes = [];
            public function handle(Envelope $envelope, StackInterface $stack): Envelope
            {
                $this->envelopes[] = $envelope;

                return $envelope;
            }
        };
        $messageBus = new MessageBus([$fakeMiddleware]);

        $service = new CreateListUsecase($repo, $messageBus, new RamseyUuidFactory());
        $service(new CreateList('My list'));

        static::assertCount(1, $repo->getAll());
        $lists = $repo->getAll();
        static::assertSame('My list', reset($lists)->getName());
        static::assertInstanceOf(ListCreatedEvent::class, $fakeMiddleware->envelopes[0]->getMessage());
    }
}
