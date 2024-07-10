<?php

declare(strict_types=1);

namespace Maximaster\BitrixEventDispatcher;

use Bitrix\Main\EventManager;
use Maximaster\BitrixEventDispatcher\Contract\EventDescriber;
use Maximaster\BitrixEventDispatcher\Contract\EventListenerRegisterer as EventListenerRegistererInterface;

class EventListenerRegisterer implements EventListenerRegistererInterface
{
    private EventDescriber $describer;

    public function __construct(EventDescriber $describer)
    {
        $this->describer = $describer;
    }

    public function registerEvent(string $eventClass, callable $handler, int $sort): void
    {
        $manager = EventManager::getInstance();

        $event = $this->describer->describeEvent($eventClass);

        $manager->addEventHandler($event->modue()->getValue(), $event->name(), $handler, false, $sort);
    }
}
