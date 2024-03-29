<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMDocument;
use DOMNode;
use Illuminate\Support\Collection;
use SoftRules\PHP\Interfaces\IExpression;
use SoftRules\PHP\Interfaces\ISoftRules_Base;
use SoftRules\PHP\Traits\ParsedFromXml;

class Expression implements IExpression
{
    use ParsedFromXml;

    private string $description = '';
    private bool $startValue = true;
    /**
     * @var Collection<int, Condition>
     */
    public readonly Collection $conditions;

    public function __construct()
    {
        $this->conditions = new Collection();
    }

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
            $this->startValue = strtolower($startValue) === 'true';
        }
    }

    public function getStartValue(): bool
    {
        return $this->startValue;
    }

    public function addCondition(Condition $condition): void
    {
        $this->conditions->add($condition);
    }

    public function clean(): void
    {
        //
    }

    /**
     * @param Collection<ISoftRules_Base> $items
     */
    public function value(Collection $items, DOMDocument $UserinterfaceData): bool
    {
        $res = $this->getStartValue();

        foreach ($this->conditions as $condition) {
            $res = $condition->value($res, $items, $UserinterfaceData);
        }

        return $res;
    }

    public function writeXml($writer): void
    {
        //
    }

    public function parse(DOMNode $node): self
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
                        $this->addCondition(Condition::createFromDomNode($conditionNode));
                    }
                    break;
            }
        }

        return $this;
    }
}
