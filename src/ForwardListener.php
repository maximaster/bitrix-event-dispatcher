<?php

declare(strict_types=1);

namespace Maximaster\BitrixEventDispatcher;

use Maximaster\BitrixEventDispatcher\Contract\Event;
use Maximaster\BitrixEventDispatcher\Contract\ProductiveEvent;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Слушатель создающий событие из данных Битриксового события и пробрасывающий
 * его в dispatcher.
 *
 * При этом аргументы события Битрикс напрямую становятся аргументами
 * конструктора класса события.
 */
class ForwardListener
{
    private ReflectionClass $eventClass;
    private EventDispatcherInterface $dispatcher;

    /**
     * @throws ReflectionException
     *
     * @psalm-param class-string<Event> $eventClass
     */
    public function __construct(string $eventClass, EventDispatcherInterface $dispatcher)
    {
        $this->eventClass = new ReflectionClass($eventClass);
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return mixed|null
     *
     * @throws ReflectionException
     */
    public function __invoke(mixed &...$args)
    {
        $event = $this->eventClass->newInstanceArgs($args);
        $this->dispatcher->dispatch($event);

        if ($event instanceof ProductiveEvent) {
            return $event->product();
        }

        return null;
    }
}
