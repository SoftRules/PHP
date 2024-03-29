<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use SoftRules\PHP\Enums\eButtonType;
use SoftRules\PHP\Interfaces\ButtonItemInterface;
use SoftRules\PHP\Interfaces\ExpressionInterface;
use SoftRules\PHP\Interfaces\ParameterInterface;
use SoftRules\PHP\Interfaces\Renderable;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;

class Button implements ButtonItemInterface, Renderable
{
    use HasCustomProperties, ParsedFromXml;

    private string $buttonID;
    private string $text = '';
    private $hint;
    private $type;
    private $description;
    private $displayType;
    private $skipFormValidation;
    private ExpressionInterface $visibleExpression;
    private ParameterInterface $parameter;

    public function __construct()
    {
        $this->setVisibleExpression(new Expression());
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

    public function setVisibleExpression(ExpressionInterface $visibleExpression): void
    {
        $this->visibleExpression = $visibleExpression;
    }

    public function getVisibleExpression(): ExpressionInterface
    {
        return $this->visibleExpression;
    }

    public function setParameter(ParameterInterface $parameter): void
    {
        $this->parameter = $parameter;
    }

    public function getParameter(): ParameterInterface
    {
        return $this->parameter;
    }

    public function parse(DOMNode $node): self
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case 'ButtonID':
                    $this->setButtonID($item->nodeValue);
                    break;
                case 'Hint':
                    $this->setHint($item->nodeValue);
                    break;
                case 'Text':
                    $this->setText($item->nodeValue);
                    break;
                case 'Type':
                    $this->setType($item->nodeValue);
                    break;
                case 'Description':
                    $this->setDescription($item->nodeValue);
                    break;
                case 'DisplayType':
                    $this->setDisplayType($item->nodeValue);
                    break;
                case 'SkipFormValidation':
                    $this->setSkipFormValidation($item->nodeValue);
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
                    echo "Button Not implemented yet: {$item->nodeName}<br>";
                    break;
            }
        }

        return $this;
    }

    public function render(): string
    {
        $styleType = '';

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
                case 'styletype':
                    $styleType = " data-styleType='" . $customProperty->getValue() . "'";
                    break;
                case 'align':
                    $align = " data-align='" . $customProperty->getValue() . "'";
                    break;
                default:
                    break;
            }
        }

        $buttonFunction = match ($this->getType()) {
            eButtonType::submit => 'processButton',
            eButtonType::navigate, eButtonType::update => 'updateButton',
            default => '',
        };

        $html = '<div>';
        $html .= "<button type=\"button\" class=\"{$buttonFunction} btn btn-default\" data-type='button' data-id=\"{$this->getButtonID()}\">{$this->getText()}</button>";
        $html .= '</div>';

        return $html;
    }
}
