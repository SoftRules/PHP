<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use SoftRules\PHP\Interfaces\ExpressionInterface;
use SoftRules\PHP\Interfaces\ItemWithCustomProperties;
use SoftRules\PHP\Interfaces\LabelItemInterface;
use SoftRules\PHP\Interfaces\ParameterInterface;
use SoftRules\PHP\Interfaces\Renderable;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;

class Label implements ItemWithCustomProperties, LabelItemInterface, Renderable
{
    use HasCustomProperties;
    use ParsedFromXml;

    private string $labelID;
    private string $text;
    private $description;
    private $displayType;
    private ParameterInterface $parameter;
    private ExpressionInterface $visibleExpression;

    public function __construct()
    {
        $this->setVisibleExpression(new Expression());
    }

    public function setLabelID(string $labelID): void
    {
        $this->labelID = $labelID;
    }

    public function getLabelID(): string
    {
        return $this->labelID;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setDisplayType($displayType): void
    {
        $this->displayType = $displayType;
    }

    public function getDisplayType()
    {
        return $this->displayType;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setParameter(ParameterInterface $parameter): void
    {
        $this->parameter = $parameter;
    }

    public function getParameter(): ParameterInterface
    {
        return $this->parameter;
    }

    public function setVisibleExpression(ExpressionInterface $visibleExpression): void
    {
        $this->visibleExpression = $visibleExpression;
    }

    public function getVisibleExpression(): ExpressionInterface
    {
        return $this->visibleExpression;
    }

    public function parse(DOMNode $node): self
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case 'LabelID':
                    $this->setLabelID($item->nodeValue);
                    break;
                case 'Text':
                    $this->setText($item->nodeValue);
                    break;
                case 'Description':
                    $this->setDescription($item->nodeValue);
                    break;
                case 'DisplayType':
                    $this->setDisplayType($item->nodeValue);
                    break;
                case 'CustomProperties':
                    foreach ($item->childNodes as $cp) {
                        $this->addCustomProperty(CustomProperty::createFromDomNode($cp));
                    }
                    break;
                case 'Parameter':
                    $this->setParameter(Parameter::createFromDomNode($item));
                    break;
                case 'VisibleExpression':
                    $this->setVisibleExpression(Expression::createFromDomNode($item));
                    break;
                default:
                    echo 'Label Not implemented yet:' . $item->nodeName . '<br>';
                    break;
            }
        }

        return $this;
    }

    public function render(): string
    {
        return "{$this->getText()}<br/>";
    }
}
