<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMDocument;
use DOMElement;
use Illuminate\Support\Collection;
use SoftRules\PHP\Contracts\UI\ConditionContract;
use SoftRules\PHP\Contracts\UI\ExpressionContract;
use SoftRules\PHP\Traits\ParsedFromXml;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;

class Expression implements ExpressionContract
{
    use ParsedFromXml;

    private string $description = '';

    private bool $startValue = true;

    /**
     * @var Collection<int, ConditionContract>
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
        $this->startValue = is_bool($startValue) ? $startValue : strtolower($startValue) === 'true';
    }

    public function getStartValue(): bool
    {
        return $this->startValue;
    }

    public function addCondition(ConditionContract $condition): void
    {
        $this->conditions->add($condition);
    }

    public function clean(): void
    {
        //
    }

    public function value(UiComponentsCollection $components, DOMDocument $userInterfaceData): bool
    {
        $res = $this->getStartValue();

        foreach ($this->conditions as $condition) {
            $res = $condition->value($res, $components, $userInterfaceData);
        }

        return $res;
    }

    public function writeXml($writer): void
    {
        //
    }

    public function parse(DOMElement $DOMElement): static
    {
        foreach ($DOMElement->childNodes as $childNode) {
            switch ($childNode->nodeName) {
                case 'StartValue':
                    $this->setStartValue($childNode->nodeValue);
                    break;
                case 'Description':
                    $this->setDescription($childNode->nodeValue);
                    break;
                case 'Conditions':
                    foreach ($childNode->childNodes as $conditionNode) {
                        $this->addCondition(Condition::createFromDomNode($conditionNode));
                    }

                    break;
            }
        }

        return $this;
    }
}
