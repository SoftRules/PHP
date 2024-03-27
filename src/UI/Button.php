<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use SoftRules\PHP\Enums\eButtonType;
use SoftRules\PHP\Interfaces\IButton;
use SoftRules\PHP\Interfaces\IExpression;

class Button implements IButton
{
    private string $ButtonID;
    private string $Text = '';
    private $Hint;
    private $Type;
    private $Description;
    private $DisplayType;
    private $SkipFormValidation;
    private array $CustomProperties = [];
    private $VisibleExpression;
    private $Parameter;

    public function __construct()
    {
        $this->setVisibleExpression(new Expression());
    }

    public function setButtonID(string $ButtonID): void
    {
        $this->ButtonID = $ButtonID;
    }

    public function getButtonID(): string
    {
        return $this->ButtonID;
    }

    public function setText(string $Text): void
    {
        $this->Text = $Text;
    }

    public function getText(): string
    {
        return $this->Text;
    }

    public function setHint($Hint): void
    {
        $this->Hint = $Hint;
    }

    public function getHint()
    {
        return $this->Hint;
    }

    public function setType(eButtonType|string $Type): void
    {
        if ($Type instanceof eButtonType) {
            $this->Type = $Type;
            return;
        }

        $this->Type = eButtonType::from(strtolower($Type));
    }

    public function getType(): eButtonType
    {
        return $this->Type;
    }

    public function setDisplayType($DisplayType): void
    {
        $this->DisplayType = $DisplayType;
    }

    public function getDisplayType()
    {
        return $this->DisplayType;
    }

    public function setSkipFormValidation($SkipFormValidation): void
    {
        $this->SkipFormValidation = $SkipFormValidation;
    }

    public function getSkipFormValidation()
    {
        return $this->SkipFormValidation;
    }

    public function setCustomProperties(array $CustomProperties): void
    {
        $this->CustomProperties = $CustomProperties;
    }

    public function getCustomProperties(): array
    {
        return $this->CustomProperties;
    }

    public function setDescription(string $Description): void
    {
        $this->Description = $Description;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setVisibleExpression(IExpression $VisibleExpression): void
    {
        $this->VisibleExpression = $VisibleExpression;
    }

    public function getVisibleExpression(): IExpression
    {
        return $this->VisibleExpression;
    }

    public function setParameter(Parameter $Parameter): void
    {
        $this->Parameter = $Parameter;
    }

    public function getParameter(): Parameter
    {
        return $this->Parameter;
    }

    public function parse(DOMNode $node): void
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case "ButtonID":
                    $this->setButtonID($item->nodeValue);
                    break;
                case "Hint":
                    $this->setHint($item->nodeValue);
                    break;
                case "Text":
                    $this->setText($item->nodeValue);
                    break;
                case "Type":
                    $this->setType($item->nodeValue);
                    break;
                case "Description":
                    $this->setDescription($item->nodeValue);
                    break;
                case "DisplayType":
                    $this->setDisplayType($item->nodeValue);
                    break;
                case "SkipFormValidation":
                    $this->setSkipFormValidation($item->nodeValue);
                    break;
                case "CustomProperties":
                    foreach ($item->childNodes as $cp) {
                        $customProperty = new CustomProperty();
                        $customProperty->parse($cp);
                        $this->CustomProperties[] = $customProperty;
                    }
                    break;
                case "Parameter":
                    $Parameter = new Parameter();
                    $Parameter->parse($item);
                    $this->setParameter($Parameter);
                    break;
                case 'VisibleExpression':
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setVisibleExpression($expression);
                    break;
                default:
                    echo "Button Not implemented yet: {$item->nodeName}<br>";
                    break;
            }
        }
    }
}
