<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use SoftRules\PHP\Enums\eButtonType;

interface ButtonItemInterface extends BaseItemInterface, ItemWithCustomProperties
{
    public function setButtonID(string $buttonID): void;

    public function getButtonID(): string;

    public function setText(string $text): void;

    public function getText(): string;

    public function setHint($hint): void;

    public function getHint();

    public function setType(eButtonType|string $type): void;

    public function getType(): eButtonType;

    public function setParameter(ParameterInterface $parameter): void;

    public function getParameter(): ParameterInterface;
}
