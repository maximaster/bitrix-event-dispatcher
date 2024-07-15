<?php

declare(strict_types=1);

namespace Maximaster\BitrixEventDispatcher;

use Maximaster\BitrixEventDispatcher\Contract\Event;
use Maximaster\BitrixEventDispatcher\Contract\EventListenerRegisterer;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Диспетчер событий.
 */
class EventDispatcher implements EventDispatcherInterface
{
    private EventDispatcherInterface $upstream;
    private EventListenerRegisterer $registerer;

    /**
     * TODO подумать как избежать состояния, в идеале, надо брать его из
     *      Битрикса напрямую через отдельный сервис, чтобы сброс обработчиков
     *      там, влиял и на диспетчер.
     *
     * @var string[]
     */
    private static array $registeredForwarders = [];

    public function __construct(EventDispatcherInterface $upstream, EventListenerRegisterer $registerer)
    {
        $this->upstream = $upstream;
        $this->registerer = $registerer;
    }

    /**
     * @throws ReflectionException
     */
    public function addListener(string $eventName, callable $listener, int $priority = 0): void
    {
        if (
            class_exists($eventName)
            && is_subclass_of($eventName, Event::class)
            // Не добавляем дублирующие forward-слушатели, иначе событие будет
            // вызываться столько раз, сколько добавлено слушателей.
            && in_array($eventName, self::$registeredForwarders, true) === false
        ) {
            $this->registerer->registerEvent($eventName, new ForwardListener($eventName, $this), $priority);
            self::$registeredForwarders[] = $eventName;
        }

        $this->upstream->addListener($eventName, $listener, $priority);
    }

    public function addSubscriber(EventSubscriberInterface $subscriber): void
    {
        $temporaryDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
        $temporaryDispatcher->addSubscriber($subscriber);

        foreach ($temporaryDispatcher->getListeners() as $eventName => $eventListeners) {
            foreach ($eventListeners as $listener) {
                $this->addListener(
                    $eventName,
                    $listener,
                    $temporaryDispatcher->getListenerPriority($eventName, $listener) ?? 0
                );
            }
        }
    }

    public function removeListener(string $eventName, callable $listener): void
    {
        $this->upstream->removeListener($eventName, $listener);
    }

    public function removeSubscriber(EventSubscriberInterface $subscriber): void
    {
        $this->upstream->removeSubscriber($subscriber);
    }

    public function getListeners(?string $eventName = null): array
    {
        return $this->upstream->getListeners($eventName);
    }

    public function dispatch(object $event, ?string $eventName = null): object
    {
        return $this->upstream->dispatch($event, $eventName);
    }

    public function getListenerPriority(string $eventName, callable $listener): ?int
    {
        return $this->upstream->getListenerPriority($eventName, $listener);
    }

    public function hasListeners(?string $eventName = null): bool
    {
        return $this->upstream->hasListeners($eventName);
    }
}
