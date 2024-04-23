<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Components;

use DOMElement;
use SoftRules\PHP\Contracts\Renderable;
use SoftRules\PHP\Contracts\UI\Components\LabelComponentContract;
use SoftRules\PHP\Contracts\UI\ComponentWithCustomPropertiesContract;
use SoftRules\PHP\Contracts\UI\ExpressionContract;
use SoftRules\PHP\Contracts\UI\ParameterContract;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;
use SoftRules\PHP\UI\CustomProperty;
use SoftRules\PHP\UI\Expression;
use SoftRules\PHP\UI\Parameter;
use SoftRules\PHP\UI\Style\LabelComponentStyle;
use SoftRules\PHP\Enums\eGroupType;

class Label implements ComponentWithCustomPropertiesContract, LabelComponentContract, Renderable
{
    use HasCustomProperties;
    use ParsedFromXml;

    public static ?LabelComponentStyle $style = null;

    private string $labelID;

    private string $text;

    private string $description;

    private $displayType;

    private ParameterContract $parameter;

    private ExpressionContract $visibleExpression;
    private ?eGroupType $parentGroupType = eGroupType::none;
    public function __construct()
    {
        $this->setVisibleExpression(new Expression());
    }

    public static function setStyle(LabelComponentStyle $style): void
    {
        self::$style = $style;
    }

    public function getStyle(): LabelComponentStyle
    {
        return self::$style ?? LabelComponentStyle::bootstrapThree();
    }

    public function setLabelID(string $labelID): void
    {
        $this->labelID = str_replace('|', '_', $labelID);
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

    public function setParameter(ParameterContract $parameter): void
    {
        $this->parameter = $parameter;
    }

    public function getParameter(): ParameterContract
    {
        return $this->parameter;
    }

    public function setVisibleExpression(ExpressionContract $visibleExpression): void
    {
        $this->visibleExpression = $visibleExpression;
    }

    public function getVisibleExpression(): ExpressionContract
    {
        return $this->visibleExpression;
    }
    public function setParentGroupType(eGroupType $parentGroupType): void
    {
        $this->parentGroupType = $parentGroupType;
    }
    
    public function getParentGroupType(): ?eGroupType
    {
        return $this->parentGroupType;
    }
    public function parse(DOMElement $DOMElement): static
    {
        /** @var DOMElement $childNode */
        foreach ($DOMElement->childNodes as $childNode) {
            switch ($childNode->nodeName) {
                case 'LabelID':
                    $this->setLabelID($childNode->nodeValue);
                    break;
                case 'Text':
                    $this->setText($childNode->nodeValue);
                    break;
                case 'Description':
                    $this->setDescription($childNode->nodeValue);
                    break;
                case 'DisplayType':
                    $this->setDisplayType($childNode->nodeValue);
                    break;
                case 'CustomProperties':
                    /** @var DOMElement $grandChildNode */
                    foreach ($childNode->childNodes as $grandChildNode) {
                        $this->addCustomProperty(CustomProperty::createFromDomNode($grandChildNode));
                    }

                    break;
                case 'Parameter':
                    $this->setParameter(Parameter::createFromDomNode($childNode));
                    break;
                case 'VisibleExpression':
                    $this->setVisibleExpression(Expression::createFromDomNode($childNode));
                    break;
                default:
                    echo 'Label Not implemented yet:' . $childNode->nodeName . '<br>';
                    break;
            }
        }

        return $this;
    }

    public function render(): string
    {
        $html = "";
        if ($this->getParentGroupType() === eGroupType::row)
        {
            $html .= "<td class='sr-table-td'>";
        }
        
        //alignment
        $align = "";
        $valign = "";
        if ($this->getCustomPropertyByName('align')?->getValue() !== null)
        {
            $align = "sr-align-{$this->getCustomPropertyByName('align')?->getValue()}";
        }
        if ($this->getCustomPropertyByName('valign')?->getValue() !== null)
        {
            $valign = " sr-vertical-align-{$this->getCustomPropertyByName('valign')?->getValue()}";
        }

        $html .=
                <<<HTML
                <span data-id="{$this->getLabelID()}"
                       class="sr-label {$this->getStyle()->default->class} {$align} {$valign}"
                       style="{$this->getStyle()->default->inlineStyle}">
                       {$this->getText()}            
                </span>              
                HTML;

        if ($this->getParentGroupType() === eGroupType::row)
        {
            $html .= "</td>";
        }

    return $html;
    }
}
