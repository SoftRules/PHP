<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts\UI\Components;

use SoftRules\PHP\Contracts\UI\ParameterContract;
use SoftRules\PHP\Contracts\UI\UiComponentContract;

interface LabelComponentContract extends UiComponentContract
{
    public function setLabelID(string $labelID): void;

    public function getLabelID(): string;

    public function setText(string $text): void;

    public function getText(): string;

    public function setParameter(ParameterContract $parameter): void;

    public function getParameter(): ParameterContract;
}
