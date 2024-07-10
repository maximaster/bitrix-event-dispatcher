<?php

declare(strict_types=1);

namespace Maximaster\BitrixEventDispatcher\Contract;

interface EventListenerRegisterer
{
    /**
     * Регистрирует обработчик пакетного события в Битрикс.
     */
    public function registerEvent(string $eventClass, callable $handler, int $sort): void;
}
