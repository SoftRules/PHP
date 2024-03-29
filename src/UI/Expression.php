<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMDocument;
use DOMNode;
use Illuminate\Support\Collection;
use SoftRules\PHP\Interfaces\BaseItemInterface;
use SoftRules\PHP\Interfaces\ConditionInterface;
use SoftRules\PHP\Interfaces\ExpressionInterface;
use SoftRules\PHP\Traits\ParsedFromXml;

class Expression implements ExpressionInterface
{
    use ParsedFromXml;

    private string $description = '';
    private bool $startValue = true;
    /**
     * @var Collection<int, ConditionInterface>
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

    public function addCondition(ConditionInterface $condition): void
    {
        $this->conditions->add($condition);
    }

    public function clean(): void
    {
        //
    }

    /**
     * @param Collection<BaseItemInterface> $items
     */
    public function value(Collection $items, DOMDocument $userInterfaceData): bool
    {
        $res = $this->getStartValue();

        foreach ($this->conditions as $condition) {
            $res = $condition->value($res, $items, $userInterfaceData);
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
