<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts;

use SoftRules\PHP\UI\Collections\UiComponentsCollection;

interface RenderableWrapper
{
    public function renderOpeningTags($components, $userInterfaceData): string;

    public function renderClosingTags(): string;

    public function getComponents(): UiComponentsCollection;
}
