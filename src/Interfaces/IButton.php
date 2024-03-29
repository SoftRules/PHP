<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use SoftRules\PHP\Enums\eButtonType;
use SoftRules\PHP\UI\Parameter;

interface IButton extends ISoftRules_Base, ItemWithCustomProperties
{
    public function setButtonID(string $ButtonID): void;

    public function getButtonID(): string;

    public function setText(string $Text): void;

    public function getText(): string;

    public function setHint($Hint): void;

    public function getHint();

    public function setType(eButtonType|string $Type): void;

    public function getType(): eButtonType;

    public function setParameter(Parameter $Parameter): void;

    public function getParameter(): Parameter;
}
