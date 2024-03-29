<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use Illuminate\Support\Collection;

interface ItemWithCustomProperties
{
    public function addCustomProperty(CustomPropertyInterface $customProperty): void;

    /**
     * @return Collection<int, CustomPropertyInterface>
     */
    public function getCustomProperties(): Collection;
}
