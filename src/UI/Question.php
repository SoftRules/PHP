<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use SoftRules\PHP\Interfaces\IExpression;
use SoftRules\PHP\Interfaces\IQuestion;
use SoftRules\PHP\Interfaces\IRestrictions;

class Question implements IQuestion
{
    private $QuestionID;
    private $Name;
    private $Value;
    private $Description;
    private $Placeholder;
    private $Tooltip;
    private $HelpText;
    private $DefaultState;
    private $IncludeInvisibleQuestion;
    private $DataType;
    private $DisplayType;
    private $DisplayOnly;
    private IRestrictions $Restrictions;
    private $Parameter;
    private $ElementPath;
    private $UpdateUserinterface;
    private $InvalidMessage;
    private $CustomProperties;
    private $ReadyForProcess;
    private $CoreValue;
    private array $TextValues = [];
    private IExpression $ValidExpression;
    private IExpression $DefaultStateExpression;
    private IExpression $RequiredExpression;
    private IExpression $UpdateExpression;
    private IExpression $VisibleExpression;

    function __construct()
    {
        $this->setVisibleExpression(new Expression());
        $this->setValidExpression(new Expression());
        $this->setRequiredExpression(new Expression());
        $this->setUpdateExpression(new Expression());
        $this->setDefaultStateExpression(new Expression());
    }

    public function setQuestionID($QuestionID): void
    {
        $this->QuestionID = $QuestionID;
    }

    public function getQuestionID()
    {
        return $this->QuestionID;
    }

    public function setName($Name): void
    {
        $this->Name = $Name;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setValue($Value): void
    {
        $this->Value = $Value;
    }

    public function getValue()
    {
        return $this->Value;
    }

    public function setDescription(string $Description): void
    {
        $this->Description = $Description;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setPlaceholder($Placeholder): void
    {
        $this->Placeholder = $Placeholder;
    }

    public function getPlaceholder()
    {
        return $this->Placeholder;
    }

    public function setTooltip($Tooltip): void
    {
        $this->Tooltip = $Tooltip;
    }

    public function getTooltip()
    {
        return $this->Tooltip;
    }

    public function setHelpText($HelpText): void
    {
        $this->HelpText = $HelpText;
    }

    public function getHelpText()
    {
        return $this->HelpText;
    }

    public function setDefaultState($DefaultState): void
    {
        $this->DefaultState = $DefaultState;
    }

    public function getDefaultState()
    {
        return $this->DefaultState;
    }

    public function setCoreValue($CoreValue): void
    {
        $this->CoreValue = $CoreValue;
    }

    public function getCoreValue()
    {
        return $this->CoreValue;
    }

    public function setIncludeInvisibleQuestion($IncludeInvisibleQuestion): void
    {
        $this->IncludeInvisibleQuestion = $IncludeInvisibleQuestion;
    }

    public function getIncludeInvisibleQuestion()
    {
        return $this->IncludeInvisibleQuestion;
    }

    public function setDataType($DataType): void
    {
        $this->DataType = $DataType;
    }

    public function getDataType()
    {
        return $this->DataType;
    }

    public function setDisplayType($DisplayType): void
    {
        $this->DisplayType = $DisplayType;
    }

    public function getDisplayType()
    {
        return $this->DisplayType;
    }

    public function setDisplayOnly($DisplayOnly): void
    {
        $this->DisplayOnly = $DisplayOnly;
    }

    public function getDisplayOnly()
    {
        return $this->DisplayOnly;
    }

    public function setRestrictions(IRestrictions $Restrictions): void
    {
        $this->Restrictions = $Restrictions;
    }

    public function getRestrictions(): IRestrictions
    {
        return $this->Restrictions;
    }

    public function setParameter(Parameter $Parameter): void
    {
        $this->Parameter = $Parameter;
    }

    public function getParameter(): Parameter
    {
        return $this->Parameter;
    }

    public function setElementPath($ElementPath): void
    {
        $this->ElementPath = $ElementPath;
    }

    public function getElementPath()
    {
        return $this->ElementPath;
    }

    public function setUpdateUserinterface($UpdateUserinterface): void
    {
        $this->UpdateUserinterface = $UpdateUserinterface;
    }

    public function getUpdateUserinterface()
    {
        return $this->UpdateUserinterface;
    }

    public function setInvalidMessage($InvalidMessage): void
    {
        $this->InvalidMessage = $InvalidMessage;
    }

    public function getInvalidMessage()
    {
        return $this->InvalidMessage;
    }

    public function setCustomProperties($CustomProperties): void
    {
        $this->CustomProperties = $CustomProperties;
    }

    public function getCustomProperties()
    {
        return $this->CustomProperties;
    }

    public function setDefaultStateExpression(IExpression $DefaultStateExpression): void
    {
        $this->DefaultStateExpression = $DefaultStateExpression;
    }

    public function getDefaultStateExpression()
    {
        return $this->DefaultStateExpression;
    }

    public function setRequiredExpression(IExpression $RequiredExpression): void
    {
        $this->RequiredExpression = $RequiredExpression;
    }

    public function getRequiredExpression()
    {
        return $this->RequiredExpression;
    }

    public function setUpdateExpression(IExpression $UpdateExpression): void
    {
        $this->UpdateExpression = $UpdateExpression;
    }

    public function getUpdateExpression()
    {
        return $this->UpdateExpression;
    }

    public function setValidExpression(IExpression $ValidExpression): void
    {
        $this->ValidExpression = $ValidExpression;
    }

    public function getValidExpression()
    {
        return $this->ValidExpression;
    }

    public function setVisibleExpression(IExpression $VisibleExpression): void
    {
        $this->VisibleExpression = $VisibleExpression;
    }

    public function getVisibleExpression(): IExpression
    {
        return $this->VisibleExpression;
    }

    public function setReadyForProcess($ReadyForProcess): void
    {
        $this->ReadyForProcess = $ReadyForProcess;
    }

    public function getReadyForProcess()
    {
        return $this->ReadyForProcess;
    }

    public function setTextValues($TextValues): void
    {
        $this->TextValues = $TextValues;
    }

    public function getTextValues(): array
    {
        return $this->TextValues;
    }

    public function parse(DOMNode $node): void
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case "QuestionID":
                    $this->setQuestionID($item->nodeValue);
                    break;
                case "Name":
                    $this->setName($item->nodeValue);
                    break;
                case "Value":
                    $this->setValue($item->nodeValue);
                    break;
                case "Description":
                    $this->setDescription($item->nodeValue);
                    break;
                case "Placeholder":
                    $this->setPlaceholder($item->nodeValue);
                    break;
                case "Tooltip":
                    $this->setTooltip($item->nodeValue);
                    break;
                case "HelpText":
                    $this->setHelpText($item->nodeValue);
                    break;
                case "DefaultState":
                    $this->setDefaultState($item->nodeValue);
                    break;
                case "IncludeInvisibleQuestion":
                    $this->setIncludeInvisibleQuestion($item->nodeValue);
                    break;
                case "DataType":
                    $this->setDataType($item->nodeValue);
                    break;
                case "DisplayType":
                    $this->setDisplayType($item->nodeValue);
                    break;
                case "DisplayOnly":
                    $this->setDisplayOnly($item->nodeValue);
                    break;
                case "CoreValue":
                    $this->setCoreValue($item->nodeValue);
                    break;
                case "Restrictions":
                    $this->Restrictions = new Restrictions();

                    foreach ($item->childNodes as $restriction) {
                        switch ($restriction->nodeName) {
                            case "enumerationValues":
                                $this->Restrictions->setEnumerationValues($restriction->nodeValue);
                                break;
                            case "fractionDigits":
                                $this->Restrictions->setFractionDigits($restriction->nodeValue);
                                break;
                            case 'length':
                                $this->Restrictions->setLength($restriction->nodeValue);
                                break;
                            case 'maxExclusive':
                                $this->Restrictions->setMaxExclusive($restriction->nodeValue);
                                break;
                            case 'minExclusive':
                                $this->Restrictions->setMinExclusive($restriction->nodeValue);
                                break;
                            case 'maxInclusive':
                                $this->Restrictions->setMaxInclusive($restriction->nodeValue);
                                break;
                            case 'minInclusive':
                                $this->Restrictions->setMinInclusive($restriction->nodeValue);
                                break;
                            case 'maxLength':
                                $this->Restrictions->setMaxLength($restriction->nodeValue);
                                break;
                            case 'minLength':
                                $this->Restrictions->setMinLength($restriction->nodeValue);
                                break;
                            case 'pattern':
                                $this->Restrictions->setPattern($restriction->nodeValue);
                                break;
                            case 'totalDigits':
                                $this->Restrictions->setTotalDigits($restriction->nodeValue);
                                break;
                            case 'whiteSpace':
                                $this->Restrictions->setWhiteSpace($restriction->nodeValue);
                                break;
                            default:
                                echo "restriction Not implemented yet:" . $restriction->nodeName . "<br>";
                                break;
                        }
                    }
                    break;
                case "Parameter":
                    $Parameter = new Parameter();
                    $Parameter->parse($item);
                    $this->setParameter($Parameter);
                    break;
                case "ElementPath":
                    $this->setElementPath($item->nodeValue);
                    break;
                case "UpdateUserInterface":
                    $this->setUpdateUserinterface($item->nodeValue);
                    break;
                case "InvalidMessage":
                    $this->setInvalidMessage($item->nodeValue);
                    break;
                case "CustomProperties":
                    foreach ($item->childNodes as $cp) {
                        $customProperty = new CustomProperty();
                        $customProperty->parse($cp);
                        $this->CustomProperties[] = $customProperty;
                    }
                    break;
                case 'VisibleExpression':
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setVisibleExpression($expression);
                    break;
                case 'RequiredExpression':
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setRequiredExpression($expression);
                    break;
                case 'ValidExpression':
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setValidExpression($expression);
                    break;
                case 'DefaultStateExpression':
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setDefaultStateExpression($expression);
                    break;
                case 'UpdateExpression':
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setUpdateExpression($expression);
                    break;
                case 'ReadyForProcess':
                    $this->setReadyForProcess($item->nodeValue);
                    break;
                case 'TextValues':
                    foreach ($item->childNodes as $textValueItem) {
                        $newItem = new TextValueItem();
                        $newItem->parse($textValueItem);
                        $this->TextValues[] = $newItem;
                    }
                    break;
                default:
                    echo "Question Not implemented yet:" . $item->nodeName . "<br>";
                    break;
            }
        }
    }
}
