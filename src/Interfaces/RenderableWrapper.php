<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use SoftRules\PHP\UI\Collections\UiComponentsCollection;

interface RenderableWrapper
{
    public function renderOpeningTags(): string;

    public function renderClosingTags(): string;

    public function getComponents(): UiComponentsCollection;
}
