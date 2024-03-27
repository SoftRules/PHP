<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMDocument;
use DOMNode;
use SoftRules\PHP\Interfaces\IExpression;

class Expression implements IExpression
{
    private string $description = '';
    private bool $startValue = true;
    private array $conditions = [];

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setStartValue(string|bool $startValue): void
    {
        if (is_bool($startValue)) {
            $this->startValue = $startValue;
        } else {
            $this->startValue = strtolower($startValue) === "true";
        }
    }

    public function getStartValue(): bool
    {
        return $this->startValue;
    }

    public function setConditions(array $conditions): void
    {
        $this->conditions = $conditions;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function Clean(): void
    {
        //
    }

    public function Value(array $Items, DOMDocument $UserinterfaceData): bool
    {
        $res = $this->getStartValue();
        foreach ($this->getConditions() as $condition) {
            $res = $condition->Value($res, $Items, $UserinterfaceData);
        }
        return $res;
    }

    public function WriteXml($writer): void
    {
        //
    }

    public function parse(DOMNode $node): void
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case 'StartValue':
                    $this->setStartValue($item->nodeValue);
                    break;
                case 'Description':
                    $this->setDescription($item->nodeValue);
                    break;
                case 'Conditions':
                    foreach ($item->childNodes as $conditionNode) {
                        $condition = new Condition();
                        $condition->parse($conditionNode);
                        $this->conditions[] = $condition;
                    }
                    break;
            }
        }
    }
}
