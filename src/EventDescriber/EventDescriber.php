<?php

declare(strict_types=1);

namespace Maximaster\BitrixEventDispatcher\EventDescriber;

use Maximaster\BitrixEnums\Main\ModuleId;
use Maximaster\BitrixEventDispatcher\Contract\Event;
use Maximaster\BitrixEventDispatcher\Contract\EventDescriber as EventDescriberInterface;
use Maximaster\BitrixEventDispatcher\Contract\EventDescription as EventDescriptionInterface;
use Maximaster\BitrixEventDispatcher\Contract\Exception\NonDescribableEventException;
use ReflectionClass;
use ReflectionException;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class EventDescriber implements EventDescriberInterface
{
    public const DEFAULT_MODULE_REGEX = '/\\\Event\\\(\S+)\\\\/';

    private string $moduleRegex;
    private string $suffix;

    /**
     * @psalm-var array<string,EventDescriptionInterface>
     */
    private array $cache = [];

    public function __construct(string $moduleRegex = self::DEFAULT_MODULE_REGEX, string $suffix = 'Event')
    {
        $this->moduleRegex = $moduleRegex;
        $this->suffix = $suffix;
    }

    /**
     * @throws NonDescribableEventException
     * @throws ReflectionException
     * @throws InvalidArgumentException
     *
     * @psalm-param class-string<Event> $eventClass
     */
    public function describeEvent(string $eventClass): EventDescriptionInterface
    {
        Assert::subclassOf($eventClass, Event::class);

        if (array_key_exists($eventClass, $this->cache)) {
            return $this->cache[$eventClass];
        }

        $match = [];
        if (preg_match($this->moduleRegex, $eventClass, $match) === false) {
            throw new NonDescribableEventException(sprintf('Невозможно определить имя модуля из %s', $eventClass));
        }

        /** @psalm-var non-empty-string  $eventModule */
        [, $eventModule] = $match;

        $eventName = (new ReflectionClass($eventClass))->getShortName();
        $eventName = preg_replace('/' . preg_quote($this->suffix) . '$/', '', $eventName);

        $this->cache[$eventClass] = new EventDescription($eventName, ModuleId::fromPascalName($eventModule));

        return $this->cache[$eventClass];
    }
}
