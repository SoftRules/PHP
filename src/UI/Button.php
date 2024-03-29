<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use SoftRules\PHP\Enums\eButtonType;
use SoftRules\PHP\Interfaces\IButton;
use SoftRules\PHP\Interfaces\IExpression;
use SoftRules\PHP\Interfaces\IParameter;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;

class Button implements IButton
{
    use HasCustomProperties, ParsedFromXml;

    private string $buttonID;
    private string $text = '';
    private $hint;
    private $type;
    private $description;
    private $displayType;
    private $skipFormValidation;
    private IExpression $visibleExpression;
    private IParameter $parameter;

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

    public function setVisibleExpression(IExpression $visibleExpression): void
    {
        $this->visibleExpression = $visibleExpression;
    }

    public function getVisibleExpression(): IExpression
    {
        return $this->visibleExpression;
    }

    public function setParameter(Parameter $parameter): void
    {
        $this->parameter = $parameter;
    }

    public function getParameter(): Parameter
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
}
