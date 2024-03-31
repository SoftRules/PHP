<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use Exception;
use SoftRules\PHP\Enums\eValueType;
use SoftRules\PHP\Interfaces\OperandInterface;
use SoftRules\PHP\Traits\ParsedFromXml;

class Operand implements OperandInterface
{
    use ParsedFromXml;

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
            'CONSTANT' => eValueType::CONSTANT,
            'ELEMENTNAME' => eValueType::ELEMENTNAME,
            'FORMULA' => eValueType::FORMULA,
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

    public function parse(DOMNode $node): static
    {
        foreach ($node->childNodes as $childNode) {
            switch ($childNode->nodeName) {
                case 'Value':
                    $this->setValue($childNode->nodeValue);
                    break;
                case 'Attributes':
                    $this->setAttributes($childNode->nodeValue);
                    break;
                case 'ElementPath':
                    $this->setElementPath($childNode->nodeValue);
                    break;
                case 'ValueType':
                    $this->setValueType($childNode->nodeValue);
                    break;
            }
        }

        return $this;
    }
}
