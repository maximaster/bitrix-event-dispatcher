<?php

declare(strict_types=1);

namespace Maximaster\BitrixEventDispatcher\Contract;

interface ProductiveEvent extends Event
{
    /**
     * Возвращает результат выполнения запроса.
     */
    public function product(): mixed;
}
