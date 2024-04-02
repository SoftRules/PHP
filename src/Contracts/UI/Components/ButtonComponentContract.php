<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts\UI\Components;

use SoftRules\PHP\Contracts\UI\ComponentWithCustomPropertiesContract;
use SoftRules\PHP\Contracts\UI\ParameterContract;
use SoftRules\PHP\Contracts\UI\UiComponentContract;
use SoftRules\PHP\Enums\eButtonType;

interface ButtonComponentContract extends ComponentWithCustomPropertiesContract, UiComponentContract
{
    public function setButtonID(string $buttonID): void;

    public function getButtonID(): string;

    public function setText(string $text): void;

    public function getText(): string;

    public function setHint($hint): void;

    public function getHint();

    public function setType(eButtonType|string $type): void;

    public function getType(): eButtonType;

    public function setParameter(ParameterContract $parameter): void;

    public function getParameter(): ParameterContract;
}
