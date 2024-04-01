<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts\UI;

use Illuminate\Support\Collection;

interface ComponentWithCustomPropertiesContract
{
    public function addCustomProperty(CustomPropertyContract $customProperty): void;

    /**
     * @return Collection<int, CustomPropertyContract>
     */
    public function getCustomProperties(): Collection;
}
