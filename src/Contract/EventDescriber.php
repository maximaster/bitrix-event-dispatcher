<?php

declare(strict_types=1);

namespace Maximaster\BitrixEventDispatcher\Contract;

interface EventDescriber
{
    /**
     * @psalm-param class-string<Event> $eventClass
     */
    public function describeEvent(string $eventClass): EventDescription;
}
