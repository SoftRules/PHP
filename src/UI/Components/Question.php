<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Components;

use DOMNode;
use Illuminate\Support\Collection;
use SoftRules\PHP\Interfaces\ComponentWithCustomProperties;
use SoftRules\PHP\Interfaces\ExpressionInterface;
use SoftRules\PHP\Interfaces\ParameterInterface;
use SoftRules\PHP\Interfaces\QuestionComponentInterface;
use SoftRules\PHP\Interfaces\Renderable;
use SoftRules\PHP\Interfaces\RestrictionsInterface;
use SoftRules\PHP\Interfaces\TextValueComponentInterface;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;
use SoftRules\PHP\UI\CustomProperty;
use SoftRules\PHP\UI\Expression;
use SoftRules\PHP\UI\Parameter;
use SoftRules\PHP\UI\Restrictions;
use SoftRules\PHP\UI\Style\QuestionComponentStyle;
use SoftRules\PHP\UI\TextValueComponent;

class Question implements ComponentWithCustomProperties, QuestionComponentInterface, Renderable
{
    use HasCustomProperties;
    use ParsedFromXml;

    public static ?QuestionComponentStyle $style = null;

    private string $questionID;

    private $name;

    private $value;

    private string $description;

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
     * @var Collection<int, TextValueComponentInterface>
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

    public static function setStyle(QuestionComponentStyle $style): void
    {
        self::$style = $style;
    }

    public function getStyle(): QuestionComponentStyle
    {
        return self::$style ?? QuestionComponentStyle::bootstrap();
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

    public function addTextValue(TextValueComponentInterface $textValue): void
    {
        $this->textValues->add($textValue);
    }

    public function parse(DOMNode $node): static
    {
        foreach ($node->childNodes as $childNode) {
            switch ($childNode->nodeName) {
                case 'QuestionID':
                    $this->setQuestionID($childNode->nodeValue);
                    break;
                case 'Name':
                    $this->setName($childNode->nodeValue);
                    break;
                case 'Value':
                    $this->setValue($childNode->nodeValue);
                    break;
                case 'Description':
                    $this->setDescription($childNode->nodeValue);
                    break;
                case 'Placeholder':
                    $this->setPlaceholder($childNode->nodeValue);
                    break;
                case 'Tooltip':
                    $this->setTooltip($childNode->nodeValue);
                    break;
                case 'HelpText':
                    $this->setHelpText($childNode->nodeValue);
                    break;
                case 'DefaultState':
                    $this->setDefaultState($childNode->nodeValue);
                    break;
                case 'IncludeInvisibleQuestion':
                    $this->setIncludeInvisibleQuestion($childNode->nodeValue);
                    break;
                case 'DataType':
                    $this->setDataType($childNode->nodeValue);
                    break;
                case 'DisplayType':
                    $this->setDisplayType($childNode->nodeValue);
                    break;
                case 'DisplayOnly':
                    $this->setDisplayOnly($childNode->nodeValue);
                    break;
                case 'CoreValue':
                    $this->setCoreValue($childNode->nodeValue);
                    break;
                case 'Restrictions':
                    $this->restrictions = new Restrictions();

                    foreach ($childNode->childNodes as $grandChildNode) {
                        switch ($grandChildNode->nodeName) {
                            case 'enumerationValues':
                                $this->restrictions->setEnumerationValues($grandChildNode->nodeValue);
                                break;
                            case 'fractionDigits':
                                $this->restrictions->setFractionDigits($grandChildNode->nodeValue);
                                break;
                            case 'length':
                                $this->restrictions->setLength($grandChildNode->nodeValue);
                                break;
                            case 'maxExclusive':
                                $this->restrictions->setMaxExclusive($grandChildNode->nodeValue);
                                break;
                            case 'minExclusive':
                                $this->restrictions->setMinExclusive($grandChildNode->nodeValue);
                                break;
                            case 'maxInclusive':
                                $this->restrictions->setMaxInclusive($grandChildNode->nodeValue);
                                break;
                            case 'minInclusive':
                                $this->restrictions->setMinInclusive($grandChildNode->nodeValue);
                                break;
                            case 'maxLength':
                                $this->restrictions->setMaxLength($grandChildNode->nodeValue);
                                break;
                            case 'minLength':
                                $this->restrictions->setMinLength($grandChildNode->nodeValue);
                                break;
                            case 'pattern':
                                $this->restrictions->setPattern($grandChildNode->nodeValue);
                                break;
                            case 'totalDigits':
                                $this->restrictions->setTotalDigits($grandChildNode->nodeValue);
                                break;
                            case 'whiteSpace':
                                $this->restrictions->setWhiteSpace($grandChildNode->nodeValue);
                                break;
                            default:
                                echo 'restriction Not implemented yet:' . $grandChildNode->nodeName . '<br>';
                                break;
                        }
                    }

                    break;
                case 'Parameter':
                    $this->setParameter(Parameter::createFromDomNode($childNode));
                    break;
                case 'ElementPath':
                    $this->setElementPath($childNode->nodeValue);
                    break;
                case 'UpdateUserInterface':
                    $this->setUpdateUserInterface($childNode->nodeValue);
                    break;
                case 'InvalidMessage':
                    $this->setInvalidMessage($childNode->nodeValue);
                    break;
                case 'CustomProperties':
                    foreach ($childNode->childNodes as $grandChildNode) {
                        $this->addCustomProperty(CustomProperty::createFromDomNode($grandChildNode));
                    }

                    break;
                case 'VisibleExpression':
                    $this->setVisibleExpression(Expression::createFromDomNode($childNode));
                    break;
                case 'RequiredExpression':
                    $this->setRequiredExpression(Expression::createFromDomNode($childNode));
                    break;
                case 'ValidExpression':
                    $this->setValidExpression(Expression::createFromDomNode($childNode));
                    break;
                case 'DefaultStateExpression':
                    $this->setDefaultStateExpression(Expression::createFromDomNode($childNode));
                    break;
                case 'UpdateExpression':
                    $this->setUpdateExpression(Expression::createFromDomNode($childNode));
                    break;
                case 'ReadyForProcess':
                    $this->setReadyForProcess($childNode->nodeValue);
                    break;
                case 'TextValues':
                    foreach ($childNode->childNodes as $grandChildNode) {
                        $this->addTextValue(TextValueComponent::createFromDomNode($grandChildNode));
                    }

                    break;
                default:
                    echo 'Question Not implemented yet:' . $childNode->nodeName . '<br>';
                    break;
            }
        }

        return $this;
    }

    public function render(): string
    {
        $html = '<li>';
        $html .= "Question: {$this->getDescription()}({$this->getName()}) Value: {$this->getValue()} UpdateUserinterface: {$this->getUpdateUserInterface()}{$this->getTextValuesDescription()}";

        return $html . '</li>';
    }

    private function getTextValuesDescription(): string
    {
        if ($this->textValues->isEmpty()) {
            return '';
        }

        $items = $this->textValues
            ->implode(fn (TextValueComponent $textValueItem): string => "{$textValueItem->getValue()}-{$textValueItem->getText()}", ', ');

        return " Aantal TextValues: {$this->textValues->count()} ({$items})";
    }
}
