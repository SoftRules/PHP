<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Components;

use DOMElement;
use SoftRules\PHP\Contracts\Renderable;
use SoftRules\PHP\Contracts\UI\Components\ButtonComponentContract;
use SoftRules\PHP\Contracts\UI\ExpressionContract;
use SoftRules\PHP\Contracts\UI\ParameterContract;
use SoftRules\PHP\Enums\eButtonType;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;
use SoftRules\PHP\UI\CustomProperty;
use SoftRules\PHP\UI\Expression;
use SoftRules\PHP\UI\Parameter;
use SoftRules\PHP\UI\Style\ButtonComponentStyle;

class Button implements ButtonComponentContract, Renderable
{
    use HasCustomProperties, ParsedFromXml;

    public static ?ButtonComponentStyle $style = null;

    private string $buttonID;

    private string $text = '';

    private $hint;

    private ?eButtonType $type = null;

    private string $description;

    private $displayType;

    private $skipFormValidation;

    private ExpressionContract $visibleExpression;

    private ParameterContract $parameter;

    public function __construct()
    {
        $this->setVisibleExpression(new Expression());
    }

    public static function setStyle(ButtonComponentStyle $style): void
    {
        self::$style = $style;
    }

    public function getStyle(): ButtonComponentStyle
    {
        return self::$style ?? ButtonComponentStyle::bootstrapThree();
    }

    public function setButtonID(string $buttonID): void
    {
        $this->buttonID = $buttonID;
    }

    public function getButtonID(): string
    {
        return $this->buttonID;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setHint($hint): void
    {
        $this->hint = $hint;
    }

    public function getHint()
    {
        return $this->hint;
    }

    public function setType(eButtonType|string $type): void
    {
        if ($type instanceof eButtonType) {
            $this->type = $type;

            return;
        }

        $this->type = eButtonType::from(strtolower($type));
    }

    public function getType(): eButtonType
    {
        return $this->type;
    }

    public function setDisplayType($displayType): void
    {
        $this->displayType = $displayType;
    }

    public function getDisplayType()
    {
        return $this->displayType;
    }

    public function setSkipFormValidation($skipFormValidation): void
    {
        $this->skipFormValidation = $skipFormValidation;
    }

    public function getSkipFormValidation()
    {
        return $this->skipFormValidation;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setVisibleExpression(ExpressionContract $visibleExpression): void
    {
        $this->visibleExpression = $visibleExpression;
    }

    public function getVisibleExpression(): ExpressionContract
    {
        return $this->visibleExpression;
    }

    public function setParameter(ParameterContract $parameter): void
    {
        $this->parameter = $parameter;
    }

    public function getParameter(): ParameterContract
    {
        return $this->parameter;
    }

    public function parse(DOMElement $DOMElement): static
    {
        /** @var DOMElement $childNode */
        foreach ($DOMElement->childNodes as $childNode) {
            switch ($childNode->nodeName) {
                case 'ButtonID':
                    $this->setButtonID($childNode->nodeValue);
                    break;
                case 'Hint':
                    $this->setHint($childNode->nodeValue);
                    break;
                case 'Text':
                    $this->setText($childNode->nodeValue);
                    break;
                case 'Type':
                    $this->setType($childNode->nodeValue);
                    break;
                case 'Description':
                    $this->setDescription($childNode->nodeValue);
                    break;
                case 'DisplayType':
                    $this->setDisplayType($childNode->nodeValue);
                    break;
                case 'SkipFormValidation':
                    $this->setSkipFormValidation($childNode->nodeValue);
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
                    echo "Button Not implemented yet: {$childNode->nodeName}<br>";
                    break;
            }
        }

        return $this;
    }

    public function render(): string
    {
        if (strtolower((string) $this->getDisplayType()) === 'tile') {
            $tile = true;
        }

        foreach ($this->getCustomProperties() as $customProperty) {
            switch (strtolower((string) $customProperty->getName())) {
                case 'nextpage':
                    $name = $customProperty->getValue();
                    break;
                case 'pictureurl':
                    $pictureUrl = $customProperty->getValue();
                    break;
                case 'width':
                    $width = $customProperty->getValue();
                    break;
                case 'height':
                    $height = $customProperty->getValue();
                    break;
                case 'align':
                    $align = " data-align='" . $customProperty->getValue() . "'";
                    break;
                default:
                    break;
            }
        }

        $styleType = $this->getCustomPropertyByName('styletype')?->getValue() ?? 'default';
        $buttonStyle = match ($styleType) {
            // TODO: Hans wat zijn de mogelijke styleTypes? Zullen we hier een enum voor maken?
            'danger' => $this->getStyle()->danger,
            default => $this->getStyle()->default,
        };

        $buttonFunction = match ($this->getType()) {
            eButtonType::submit => 'processButton',
            eButtonType::navigate, eButtonType::update => 'updateButton',
            default => '',
        };

        $html = '<div>';
        $html .= "<button type='button' class='sr-button sr-button-{$styleType} {$buttonFunction} {$buttonStyle->class}' style='{$buttonStyle->inlineStyle}' data-styleType='{$styleType}' data-type='button' data-id='{$this->getButtonID()}'>{$this->getText()}</button>";

        return $html . '</div>';
    }
}
