<?php

declare(strict_types=1);

namespace Maximaster\BitrixEventDispatcher\EventDescriber;

use Maximaster\BitrixEnums\Main\ModuleId;
use Maximaster\BitrixEventDispatcher\Contract\EventDescription as EventDescriptionInterface;

/**
 * @psalm-immutable
 */
class EventDescription implements EventDescriptionInterface
{
    public string $name;
    public ModuleId $module;

    public function __construct(string $name, ModuleId $module)
    {
        $this->name = $name;
        $this->module = $module;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function modue(): ModuleId
    {
        return $this->module;
    }
}
