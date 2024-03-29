<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use Illuminate\Support\Collection;

interface RenderableWrapper
{
    public function renderOpeningTags(): string;

    public function renderClosingTags(): string;

    /**
     * @return Collection<int, ISoftRules_Base>
     */
    public function getItems(): Collection;
}
