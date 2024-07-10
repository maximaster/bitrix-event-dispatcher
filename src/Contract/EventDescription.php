<?php

declare(strict_types=1);

namespace Maximaster\BitrixEventDispatcher\Contract;

use Maximaster\BitrixEnums\Main\ModuleId;

interface EventDescription
{
    public function name(): string;

    public function modue(): ModuleId;
}
