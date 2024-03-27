<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use Exception;
use SoftRules\PHP\Enums\eValueType;
use SoftRules\PHP\Interfaces\IOperand;

class Operand implements IOperand
{
    private mixed $value;
    private string $elementPath;
    private string $attributes;
    private eValueType $valueType;

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setElementPath(string $elementPath): void
    {
        $this->elementPath = $elementPath;
    }

    public function getElementPath(): string
    {
        return $this->elementPath;
    }

    public function setAttributes(string $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getAttributes(): string
    {
        return $this->attributes;
    }

    public function setValueType(eValueType|string|int $valueType): void //eValueType
    {
        if ($valueType instanceof eValueType) {
            $this->valueType = $valueType;
            return;
        }

        if (is_int($valueType)) {
            $this->valueType = eValueType::from($valueType);
            return;
        }

        $this->valueType = match ($valueType) {
            "CONSTANT" => eValueType::CONSTANT,
            "ELEMENTNAME" => eValueType::ELEMENTNAME,
            "FORMULA" => eValueType::FORMULA,
            default => throw new Exception('Invalid value type ' . $valueType),
        };
    }

    public function getValueType(): eValueType
    {
        return $this->valueType;
    }

    public function writeXml($writer): void
    {
        //
    }

    public function parse(DOMNode $node): void
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case 'Value':
                    $this->setValue($item->nodeValue);
                    break;
                case 'Attributes':
                    $this->setAttributes($item->nodeValue);
                    break;
                case 'ElementPath':
                    $this->setElementPath($item->nodeValue);
                    break;
                case 'ValueType':
                    $this->setValueType($item->nodeValue);
                    break;
            }
        }
    }
}
