<?php

declare(strict_types=1);

namespace Maximaster\BitrixEventDispatcher\Contract;

use Marcosh\LamPHPda\Maybe;
use Maximaster\BitrixValueObjects\Main\ModuleId;

/**
 * Интерфейс-метка для всех событий Битрикс.
 */
interface Event
{
    /**
     * Идентификатор модуля события.
     *
     * @psalm-return Maybe<ModuleId>
     */
    public static function module(): Maybe/* <ModuleId> */;

    /**
     * Имя события.
     *
     * @psalm-return non-empty-string
     */
    public static function name(): string;
}
