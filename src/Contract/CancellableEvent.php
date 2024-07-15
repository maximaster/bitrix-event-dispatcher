<?php

declare(strict_types=1);

namespace Maximaster\BitrixEventDispatcher\Contract;

use CApplicationException;

interface CancellableEvent extends Event
{
    /**
     * Отменить событие с указанным сообщением.
     *
     * @psalm-suppress UndefinedDocblockClass why:impossible-dependency
     *
     * @param string|CApplicationException $message
     *
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function cancel($message): void;
}
