<?php

declare(strict_types=1);

namespace Maximaster\BitrixEventDispatcher;

use Bitrix\Main\EventManager;
use Marcosh\LamPHPda\Maybe;
use Maximaster\BitrixEventDispatcher\Contract\Event;
use Maximaster\BitrixEventDispatcher\Contract\EventListenerRegisterer as EventListenerRegistererInterface;
use Maximaster\BitrixValueObjects\Main\ModuleId;

/**
 * Регистрирует обработчик события в Битрикс.
 */
class EventListenerRegisterer implements EventListenerRegistererInterface
{
    public function registerEvent(string $eventClass, callable $handler, int $sort): void
    {
        if (is_a($eventClass, Event::class, true) === false) {
            return;
        }

        /**
         * @var Event|string $eventClass
         *
         * @psalm-var class-string<Event> $eventClass
         *
         * @psalm-suppress UndefinedClass why:impossible-dependency
         */
        EventManager::getInstance()->addEventHandler(
            $this->resolveModuleName($eventClass::module()),
            $eventClass::name(),
            $handler,
            false,
            $sort
        );
    }

    /**
     * @psalm-param Maybe<ModuleId> $moduleId
     */
    private function resolveModuleName(Maybe /* <ModuleId> */ $moduleId): string
    {
        return $moduleId->eval('', static fn (ModuleId $moduleId) => $moduleId->__toString());
    }
}
