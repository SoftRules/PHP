<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use Illuminate\Support\Collection;
use SoftRules\PHP\Interfaces\ExpressionInterface;
use SoftRules\PHP\Interfaces\ItemWithCustomProperties;
use SoftRules\PHP\Interfaces\ParameterInterface;
use SoftRules\PHP\Interfaces\QuestionItemInterface;
use SoftRules\PHP\Interfaces\Renderable;
use SoftRules\PHP\Interfaces\RestrictionsInterface;
use SoftRules\PHP\Interfaces\TextValueItemInterface;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;

class Question implements ItemWithCustomProperties, QuestionItemInterface, Renderable
{
    use HasCustomProperties;
    use ParsedFromXml;

    private string $questionID;
    private $name;
    private $value;
    private $description;
    private $placeholder;
    private $tooltip;
    private $helpText;
    private $defaultState;
    private $includeInvisibleQuestion;
    private $dataType;
    private $displayType;
    private $displayOnly;
    private RestrictionsInterface $restrictions;
    private ParameterInterface $parameter;
    private $elementPath;
    private $updateUserInterface;
    private $invalidMessage;
    private $readyForProcess;
    private $coreValue;

    /**
     * @var Collection<int, TextValueItemInterface>
     */
    public readonly Collection $textValues;

    private ExpressionInterface $validExpression;
    private ExpressionInterface $defaultStateExpression;
    private ExpressionInterface $requiredExpression;
    private ExpressionInterface $updateExpression;
    private ExpressionInterface $visibleExpression;

    public function __construct()
    {
        $this->textValues = new Collection();

        $this->validExpression = new Expression();
        $this->defaultStateExpression = new Expression();
        $this->requiredExpression = new Expression();
        $this->updateExpression = new Expression();
        $this->visibleExpression = new Expression();
    }

    public function setQuestionID(string $questionID): void
    {
        $this->questionID = $questionID;
    }

    public function getQuestionID(): string
    {
        return $this->questionID;
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
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setPlaceholder($placeholder): void
    {
        $this->placeholder = $placeholder;
    }

    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    public function setTooltip($tooltip): void
    {
        $this->tooltip = $tooltip;
    }

    public function getTooltip()
    {
        return $this->tooltip;
    }

    public function setHelpText($helpText): void
    {
        $this->helpText = $helpText;
    }

    public function getHelpText()
    {
        return $this->helpText;
    }

    public function setDefaultState($defaultState): void
    {
        $this->defaultState = $defaultState;
    }

    public function getDefaultState()
    {
        return $this->defaultState;
    }

    public function setCoreValue($coreValue): void
    {
        $this->coreValue = $coreValue;
    }

    public function getCoreValue()
    {
        return $this->coreValue;
    }

    public function setIncludeInvisibleQuestion($includeInvisibleQuestion): void
    {
        $this->includeInvisibleQuestion = $includeInvisibleQuestion;
    }

    public function getIncludeInvisibleQuestion()
    {
        return $this->includeInvisibleQuestion;
    }

    public function setDataType($dataType): void
    {
        $this->dataType = $dataType;
    }

    public function getDataType()
    {
        return $this->dataType;
    }

    public function setDisplayType($displayType): void
    {
        $this->displayType = $displayType;
    }

    public function getDisplayType()
    {
        return $this->displayType;
    }

    public function setDisplayOnly($displayOnly): void
    {
        $this->displayOnly = $displayOnly;
    }

    public function getDisplayOnly()
    {
        return $this->displayOnly;
    }

    public function setRestrictions(RestrictionsInterface $restrictions): void
    {
        $this->restrictions = $restrictions;
    }

    public function getRestrictions(): RestrictionsInterface
    {
        return $this->restrictions;
    }

    public function setParameter(ParameterInterface $parameter): void
    {
        $this->parameter = $parameter;
    }

    public function getParameter(): ParameterInterface
    {
        return $this->parameter;
    }

    public function setElementPath($elementPath): void
    {
        $this->elementPath = $elementPath;
    }

    public function getElementPath()
    {
        return $this->elementPath;
    }

    public function setUpdateUserInterface($updateUserInterface): void
    {
        $this->updateUserInterface = $updateUserInterface;
    }

    public function getUpdateUserInterface()
    {
        return $this->updateUserInterface;
    }

    public function setInvalidMessage($invalidMessage): void
    {
        $this->invalidMessage = $invalidMessage;
    }

    public function getInvalidMessage()
    {
        return $this->invalidMessage;
    }

    public function setDefaultStateExpression(ExpressionInterface $defaultStateExpression): void
    {
        $this->defaultStateExpression = $defaultStateExpression;
    }

    public function getDefaultStateExpression(): ExpressionInterface
    {
        return $this->defaultStateExpression;
    }

    public function setRequiredExpression(ExpressionInterface $requiredExpression): void
    {
        $this->requiredExpression = $requiredExpression;
    }

    public function getRequiredExpression(): ExpressionInterface
    {
        return $this->requiredExpression;
    }

    public function setUpdateExpression(ExpressionInterface $updateExpression): void
    {
        $this->updateExpression = $updateExpression;
    }

    public function getUpdateExpression(): ExpressionInterface
    {
        return $this->updateExpression;
    }

    public function setValidExpression(ExpressionInterface $validExpression): void
    {
        $this->validExpression = $validExpression;
    }

    public function getValidExpression(): ExpressionInterface
    {
        return $this->validExpression;
    }

    public function setVisibleExpression(ExpressionInterface $visibleExpression): void
    {
        $this->visibleExpression = $visibleExpression;
    }

    public function getVisibleExpression(): ExpressionInterface
    {
        return $this->visibleExpression;
    }

    public function setReadyForProcess($readyForProcess): void
    {
        $this->readyForProcess = $readyForProcess;
    }

    public function getReadyForProcess()
    {
        return $this->readyForProcess;
    }

    public function addTextValue(TextValueItemInterface $textValue): void
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
                    $this->restrictions = new Restrictions();

                    foreach ($item->childNodes as $restriction) {
                        switch ($restriction->nodeName) {
                            case 'enumerationValues':
                                $this->restrictions->setEnumerationValues($restriction->nodeValue);
                                break;
                            case 'fractionDigits':
                                $this->restrictions->setFractionDigits($restriction->nodeValue);
                                break;
                            case 'length':
                                $this->restrictions->setLength($restriction->nodeValue);
                                break;
                            case 'maxExclusive':
                                $this->restrictions->setMaxExclusive($restriction->nodeValue);
                                break;
                            case 'minExclusive':
                                $this->restrictions->setMinExclusive($restriction->nodeValue);
                                break;
                            case 'maxInclusive':
                                $this->restrictions->setMaxInclusive($restriction->nodeValue);
                                break;
                            case 'minInclusive':
                                $this->restrictions->setMinInclusive($restriction->nodeValue);
                                break;
                            case 'maxLength':
                                $this->restrictions->setMaxLength($restriction->nodeValue);
                                break;
                            case 'minLength':
                                $this->restrictions->setMinLength($restriction->nodeValue);
                                break;
                            case 'pattern':
                                $this->restrictions->setPattern($restriction->nodeValue);
                                break;
                            case 'totalDigits':
                                $this->restrictions->setTotalDigits($restriction->nodeValue);
                                break;
                            case 'whiteSpace':
                                $this->restrictions->setWhiteSpace($restriction->nodeValue);
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
                    $this->setUpdateUserInterface($item->nodeValue);
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

    public function render(): string
    {
        $html = '<li>';
        $html .= "Question: {$this->getDescription()}({$this->getName()}) Value: {$this->getValue()} UpdateUserinterface: {$this->getUpdateUserInterface()}{$this->getTextValuesDescription()}";
        $html .= '</li>';

        return $html;
    }

    private function getTextValuesDescription(): string
    {
        if ($this->textValues->isEmpty()) {
            return '';
        }

        $items = $this->textValues
            ->implode(fn (TextValueItem $textValueItem) => "{$textValueItem->getValue()}-{$textValueItem->getText()}", ', ');

        return " Aantal TextValues: {$this->textValues->count()} ({$items})";
    }
}
