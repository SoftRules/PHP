<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Components;

use DOMNode;
use SoftRules\PHP\Interfaces\ComponentWithCustomProperties;
use SoftRules\PHP\Interfaces\ExpressionInterface;
use SoftRules\PHP\Interfaces\LabelComponentInterface;
use SoftRules\PHP\Interfaces\ParameterInterface;
use SoftRules\PHP\Interfaces\Renderable;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;
use SoftRules\PHP\UI\CustomProperty;
use SoftRules\PHP\UI\Expression;
use SoftRules\PHP\UI\Parameter;
use SoftRules\PHP\UI\Style\LabelComponentStyle;

class Label implements ComponentWithCustomProperties, LabelComponentInterface, Renderable
{
    use HasCustomProperties;
    use ParsedFromXml;

    public static ?LabelComponentStyle $style = null;

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

    public static function setStyle(LabelComponentStyle $style): void
    {
        self::$style = $style;
    }

    public function getStyle(): LabelComponentStyle
    {
        return self::$style ?? LabelComponentStyle::bootstrap();
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

    public function parse(DOMNode $node): static
    {
        foreach ($node->childNodes as $childNode) {
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
                    foreach ($childNode->childNodes as $cp) {
                        $this->addCustomProperty(CustomProperty::createFromDomNode($cp));
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
        return "{$this->getText()}<br/>";
    }
}
