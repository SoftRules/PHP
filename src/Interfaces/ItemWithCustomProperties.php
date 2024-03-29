<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use Illuminate\Support\Collection;
use SoftRules\PHP\UI\CustomProperty;

interface ItemWithCustomProperties
{
    public function addCustomProperty(CustomProperty $customProperty): void;

    public function getCustomProperties(): Collection;
}
