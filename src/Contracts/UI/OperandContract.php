<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts\UI;

use SoftRules\PHP\Enums\eValueType;

interface OperandContract
{
    public function setValue(mixed $value): void;

    public function getValue(): mixed;

    public function setElementPath(string $elementPath): void;

    public function getElementPath(): string;

    public function setAttributes(string $attributes): void;

    public function getAttributes(): string;

    public function setValueType(eValueType|int|string $valueType): void;

    public function getValueType(): eValueType;

    public function writeXml($writer): void;
}
