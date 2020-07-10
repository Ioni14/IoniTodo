<?php

namespace Domain\Common;

use Symfony\Component\Messenger\MessageBusInterface;

trait EventsRecordableTrait
{
    /**
     * @var EventInterface[]
     */
    private array $events = [];

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    public function releaseAndDispatchEvents(MessageBusInterface $bus): void
    {
        $events = $this->releaseEvents();
        foreach ($events as $event) {
            $bus->dispatch($event);
        }
    }
}
