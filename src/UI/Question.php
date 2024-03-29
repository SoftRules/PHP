<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use Illuminate\Support\Collection;
use SoftRules\PHP\Interfaces\IExpression;
use SoftRules\PHP\Interfaces\IQuestion;
use SoftRules\PHP\Interfaces\IRestrictions;
use SoftRules\PHP\Interfaces\ItemWithCustomProperties;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;

class Question implements IQuestion, ItemWithCustomProperties
{
    use HasCustomProperties;
    use ParsedFromXml;

    private $QuestionID;
    private $name;
    private $value;
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
    private $ReadyForProcess;
    private $CoreValue;

    /**
     * @var Collection<int, TextValueItem>
     */
    public readonly Collection $textValues;

    private IExpression $ValidExpression;
    private IExpression $DefaultStateExpression;
    private IExpression $RequiredExpression;
    private IExpression $UpdateExpression;
    private IExpression $VisibleExpression;

    public function __construct()
    {
        $this->textValues = new Collection();
        $this->setVisibleExpression(new Expression());
        $this->setValidExpression(new Expression());
        $this->setRequiredExpression(new Expression());
        $this->setUpdateExpression(new Expression());
        $this->setDefaultStateExpression(new Expression());
    }

    public function setQuestionID($questionID): void
    {
        $this->QuestionID = $questionID;
    }

    public function getQuestionID()
    {
        return $this->QuestionID;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setDescription(string $description): void
    {
        $this->Description = $description;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setPlaceholder($placeholder): void
    {
        $this->Placeholder = $placeholder;
    }

    public function getPlaceholder()
    {
        return $this->Placeholder;
    }

    public function setTooltip($tooltip): void
    {
        $this->Tooltip = $tooltip;
    }

    public function getTooltip()
    {
        return $this->Tooltip;
    }

    public function setHelpText($helpText): void
    {
        $this->HelpText = $helpText;
    }

    public function getHelpText()
    {
        return $this->HelpText;
    }

    public function setDefaultState($defaultState): void
    {
        $this->DefaultState = $defaultState;
    }

    public function getDefaultState()
    {
        return $this->DefaultState;
    }

    public function setCoreValue($coreValue): void
    {
        $this->CoreValue = $coreValue;
    }

    public function getCoreValue()
    {
        return $this->CoreValue;
    }

    public function setIncludeInvisibleQuestion($includeInvisibleQuestion): void
    {
        $this->IncludeInvisibleQuestion = $includeInvisibleQuestion;
    }

    public function getIncludeInvisibleQuestion()
    {
        return $this->IncludeInvisibleQuestion;
    }

    public function setDataType($dataType): void
    {
        $this->DataType = $dataType;
    }

    public function getDataType()
    {
        return $this->DataType;
    }

    public function setDisplayType($displayType): void
    {
        $this->DisplayType = $displayType;
    }

    public function getDisplayType()
    {
        return $this->DisplayType;
    }

    public function setDisplayOnly($displayOnly): void
    {
        $this->DisplayOnly = $displayOnly;
    }

    public function getDisplayOnly()
    {
        return $this->DisplayOnly;
    }

    public function setRestrictions(IRestrictions $restrictions): void
    {
        $this->Restrictions = $restrictions;
    }

    public function getRestrictions(): IRestrictions
    {
        return $this->Restrictions;
    }

    public function setParameter(Parameter $parameter): void
    {
        $this->Parameter = $parameter;
    }

    public function getParameter(): Parameter
    {
        return $this->Parameter;
    }

    public function setElementPath($elementPath): void
    {
        $this->ElementPath = $elementPath;
    }

    public function getElementPath()
    {
        return $this->ElementPath;
    }

    public function setUpdateUserinterface($updateUserinterface): void
    {
        $this->UpdateUserinterface = $updateUserinterface;
    }

    public function getUpdateUserinterface()
    {
        return $this->UpdateUserinterface;
    }

    public function setInvalidMessage($invalidMessage): void
    {
        $this->InvalidMessage = $invalidMessage;
    }

    public function getInvalidMessage()
    {
        return $this->InvalidMessage;
    }

    public function setDefaultStateExpression(IExpression $defaultStateExpression): void
    {
        $this->DefaultStateExpression = $defaultStateExpression;
    }

    public function getDefaultStateExpression()
    {
        return $this->DefaultStateExpression;
    }

    public function setRequiredExpression(IExpression $requiredExpression): void
    {
        $this->RequiredExpression = $requiredExpression;
    }

    public function getRequiredExpression()
    {
        return $this->RequiredExpression;
    }

    public function setUpdateExpression(IExpression $updateExpression): void
    {
        $this->UpdateExpression = $updateExpression;
    }

    public function getUpdateExpression()
    {
        return $this->UpdateExpression;
    }

    public function setValidExpression(IExpression $validExpression): void
    {
        $this->ValidExpression = $validExpression;
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

    public function setReadyForProcess($readyForProcess): void
    {
        $this->ReadyForProcess = $readyForProcess;
    }

    public function getReadyForProcess()
    {
        return $this->ReadyForProcess;
    }

    public function addTextValue(TextValueItem $textValue): void
    {
        $this->textValues->add($textValue);
    }

    public function parse(DOMNode $node): self
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case 'QuestionID':
                    $this->setQuestionID($item->nodeValue);
                    break;
                case 'Name':
                    $this->setName($item->nodeValue);
                    break;
                case 'Value':
                    $this->setValue($item->nodeValue);
                    break;
                case 'Description':
                    $this->setDescription($item->nodeValue);
                    break;
                case 'Placeholder':
                    $this->setPlaceholder($item->nodeValue);
                    break;
                case 'Tooltip':
                    $this->setTooltip($item->nodeValue);
                    break;
                case 'HelpText':
                    $this->setHelpText($item->nodeValue);
                    break;
                case 'DefaultState':
                    $this->setDefaultState($item->nodeValue);
                    break;
                case 'IncludeInvisibleQuestion':
                    $this->setIncludeInvisibleQuestion($item->nodeValue);
                    break;
                case 'DataType':
                    $this->setDataType($item->nodeValue);
                    break;
                case 'DisplayType':
                    $this->setDisplayType($item->nodeValue);
                    break;
                case 'DisplayOnly':
                    $this->setDisplayOnly($item->nodeValue);
                    break;
                case 'CoreValue':
                    $this->setCoreValue($item->nodeValue);
                    break;
                case 'Restrictions':
                    $this->Restrictions = new Restrictions();

                    foreach ($item->childNodes as $restriction) {
                        switch ($restriction->nodeName) {
                            case 'enumerationValues':
                                $this->Restrictions->setEnumerationValues($restriction->nodeValue);
                                break;
                            case 'fractionDigits':
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
                                echo 'restriction Not implemented yet:' . $restriction->nodeName . '<br>';
                                break;
                        }
                    }
                    break;
                case 'Parameter':
                    $this->setParameter(Parameter::createFromDomNode($item));
                    break;
                case 'ElementPath':
                    $this->setElementPath($item->nodeValue);
                    break;
                case 'UpdateUserInterface':
                    $this->setUpdateUserinterface($item->nodeValue);
                    break;
                case 'InvalidMessage':
                    $this->setInvalidMessage($item->nodeValue);
                    break;
                case 'CustomProperties':
                    foreach ($item->childNodes as $cp) {
                        $this->addCustomProperty(CustomProperty::createFromDomNode($cp));
                    }
                    break;
                case 'VisibleExpression':
                    $this->setVisibleExpression(Expression::createFromDomNode($item));
                    break;
                case 'RequiredExpression':
                    $this->setRequiredExpression(Expression::createFromDomNode($item));
                    break;
                case 'ValidExpression':
                    $this->setValidExpression(Expression::createFromDomNode($item));
                    break;
                case 'DefaultStateExpression':
                    $this->setDefaultStateExpression(Expression::createFromDomNode($item));
                    break;
                case 'UpdateExpression':
                    $this->setUpdateExpression(Expression::createFromDomNode($item));
                    break;
                case 'ReadyForProcess':
                    $this->setReadyForProcess($item->nodeValue);
                    break;
                case 'TextValues':
                    foreach ($item->childNodes as $textValueItem) {
                        $this->addTextValue(TextValueItem::createFromDomNode($textValueItem));
                    }
                    break;
                default:
                    echo 'Question Not implemented yet:' . $item->nodeName . '<br>';
                    break;
            }
        }

        return $this;
    }
}
