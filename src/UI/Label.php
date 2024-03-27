<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use SoftRules\PHP\Interfaces\IExpression;
use SoftRules\PHP\Interfaces\ILabel;

class Label implements ILabel
{
    private $LabelID;
    private $Text;
    private $Description;
    private $DisplayType;
    private $Parameter;
    private array $CustomProperties = [];
    private IExpression $VisibleExpression;

    function __construct()
    {
        $this->setVisibleExpression(new Expression());
    }

    public function setLabelID($LabelID): void
    {
        $this->LabelID = $LabelID;
    }

    public function getLabelID()
    {
        return $this->LabelID;
    }

    public function setText($Text): void
    {
        $this->Text = $Text;
    }

    public function getText()
    {
        return $this->Text;
    }

    public function setDisplayType($DisplayType): void
    {
        $this->DisplayType = $DisplayType;
    }

    public function getDisplayType()
    {
        return $this->DisplayType;
    }

    public function setCustomProperties($CustomProperties): void
    {
        $this->CustomProperties = $CustomProperties;
    }

    public function getCustomProperties()
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

    public function setParameter(Parameter $Parameter): void
    {
        $this->Parameter = $Parameter;
    }

    public function getParameter(): Parameter
    {
        return $this->Parameter;
    }

    public function setVisibleExpression(IExpression $VisibleExpression): void
    {
        $this->VisibleExpression = $VisibleExpression;
    }

    public function getVisibleExpression(): IExpression
    {
        return $this->VisibleExpression;
    }

    public function parse(DOMNode $node): void
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case "LabelID":
                    $this->setLabelID($item->nodeValue);
                    break;
                case "Text":
                    $this->setText($item->nodeValue);
                    break;
                case "Description":
                    $this->setDescription($item->nodeValue);
                    break;
                case "DisplayType":
                    $this->setDisplayType($item->nodeValue);
                    break;
                case 'CustomProperties':
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
                    echo "Label Not implemented yet:" . $item->nodeName . "<br>";
                    break;
            }
        }
    }
}
