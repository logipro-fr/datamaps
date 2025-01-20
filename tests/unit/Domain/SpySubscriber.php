<?php

namespace Datamaps\Tests\Domain;

use Phariscope\Event\EventAbstract;
use Phariscope\Event\EventSubscriber;
use Phariscope\Event\Psr14\Event;

/**
 * cet espion permettra de simplifer vos tests de publication de vos évènements
 */
class SpySubscriber implements EventSubscriber
{
    public Event $domainEvent;

    public int $handleCallCount = 0;

    /** @var array<Event> */
    public array $traces;

    public function handle(Event $aDomainEvent): bool
    {
        $this->domainEvent = $aDomainEvent;
        $this->handleCallCount++;
        $this->traces[] = $aDomainEvent;
        return true;
    }

    public function isSubscribedTo(Event $aDomainEvent): bool
    {
        return true;
    }
}
