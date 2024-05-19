<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Components;

use DOMElement;
use Illuminate\Support\Collection;
use SoftRules\PHP\Contracts\Renderable;
use SoftRules\PHP\Contracts\UI\Components\QuestionComponentContract;
use SoftRules\PHP\Contracts\UI\ComponentWithCustomPropertiesContract;
use SoftRules\PHP\Contracts\UI\ExpressionContract;
use SoftRules\PHP\Contracts\UI\ParameterContract;
use SoftRules\PHP\Contracts\UI\RestrictionsContract;
use SoftRules\PHP\Contracts\UI\TextValueComponentContract;
use SoftRules\PHP\Enums\eDataType;
use SoftRules\PHP\Enums\eDefaultState;
use SoftRules\PHP\Enums\eDisplayType;
use SoftRules\PHP\Enums\eGroupType;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;
use SoftRules\PHP\UI\CustomProperty;
use SoftRules\PHP\UI\Expression;
use SoftRules\PHP\UI\Parameter;
use SoftRules\PHP\UI\Restrictions;
use SoftRules\PHP\UI\Style\QuestionComponentStyle;
use SoftRules\PHP\UI\TextValueComponent;

class Question implements ComponentWithCustomPropertiesContract, QuestionComponentContract, Renderable
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

    private ?eDefaultState $defaultState = eDefaultState::none;

    private $includeInvisibleQuestion;

    private $dataType = eDataType::none;
    private ?eDisplayType $displayType = eDisplayType::none;

    private $displayOnly;

    private RestrictionsContract $restrictions;

    private ParameterContract $parameter;

    private $elementPath;

    private bool $updateUserInterface = false;

    private $invalidMessage = '';

    private $readyForProcess;

    private $coreValue;
    private ?eGroupType $parentGroupType = eGroupType::none;

    /**
     * @var Collection<int, TextValueComponentContract>
     */
    public readonly Collection $textValues;

    private ExpressionContract $validExpression;

    private ExpressionContract $defaultStateExpression;

    private ExpressionContract $requiredExpression;

    private ExpressionContract $updateExpression;

    private ExpressionContract $visibleExpression;

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
        return self::$style ?? QuestionComponentStyle::bootstrapThree();
    }

    public function setQuestionID(string $questionID): void
    {
        $this->questionID = str_replace('|', '_', $questionID);
    }

    public function getQuestionID(): string
    {
        return $this->questionID;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getValue(): string
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

    public function setDefaultState(eDefaultState|string $defaultState): void
    {
        if ($defaultState instanceof eDefaultState) {
            $this->defaultState = $defaultState;

            return;
        }

        $this->defaultState = eDefaultState::from(strtolower($defaultState));
    }

    public function getDefaultState(): eDefaultState
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

    public function setDataType(eDataType|string $dataType): void
    {
        if ($dataType instanceof eDataType) {
            $this->dataType = $dataType;

            return;
        }

        $this->dataType = eDataType::from(strtolower($dataType));
    }

    public function getDataType(): eDataType
    {
        return $this->dataType;
    }

    public function setDisplayType(eDisplayType|string $displayType): void
    {
        if ($displayType instanceof eDisplayType) {
            $this->displayType = $displayType;

            return;
        }

        $this->displayType = eDisplayType::from(strtolower($displayType));
    }

    public function getDisplayType(): eDisplayType
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

    public function setRestrictions(RestrictionsContract $restrictions): void
    {
        $this->restrictions = $restrictions;
    }

    public function getRestrictions(): RestrictionsContract
    {
        return $this->restrictions;
    }

    public function setParameter(ParameterContract $parameter): void
    {
        $this->parameter = $parameter;
    }

    public function getParameter(): ParameterContract
    {
        return $this->parameter;
    }

    public function setElementPath($elementPath): void
    {
        $this->elementPath = $elementPath;
    }

    public function getElementPath(): string
    {
        return $this->elementPath;
    }

    public function setUpdateUserInterface($updateUserInterface): void
    {
        if (is_string($updateUserInterface)) {
        $this->updateUserInterface = strtolower($updateUserInterface) === 'true' ?? $this->updateUserInterface = false;
        }
    }

    public function getUpdateUserInterface()
    {
        return $this->updateUserInterface;
    }

    public function setInvalidMessage($invalidMessage): void
    {
        $this->invalidMessage = $invalidMessage;
    }

    public function getInvalidMessage(): string
    {
        return $this->invalidMessage;
    }

    public function setDefaultStateExpression(ExpressionContract $defaultStateExpression): void
    {
        $this->defaultStateExpression = $defaultStateExpression;
    }

    public function getDefaultStateExpression(): ExpressionContract
    {
        return $this->defaultStateExpression;
    }

    public function setRequiredExpression(ExpressionContract $requiredExpression): void
    {
        $this->requiredExpression = $requiredExpression;
    }

    public function getRequiredExpression(): ExpressionContract
    {
        return $this->requiredExpression;
    }

    public function setUpdateExpression(ExpressionContract $updateExpression): void
    {
        $this->updateExpression = $updateExpression;
    }

    public function getUpdateExpression(): ExpressionContract
    {
        return $this->updateExpression;
    }

    public function setValidExpression(ExpressionContract $validExpression): void
    {
        $this->validExpression = $validExpression;
    }

    public function getValidExpression(): ExpressionContract
    {
        return $this->validExpression;
    }

    public function setVisibleExpression(ExpressionContract $visibleExpression): void
    {
        $this->visibleExpression = $visibleExpression;
    }

    public function getVisibleExpression(): ExpressionContract
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

    public function addTextValue(TextValueComponentContract $textValue): void
    {
        $this->textValues->add($textValue);
    }

    public function setParentGroupType(eGroupType $parentGroupType): void
    {
        $this->parentGroupType = $parentGroupType;
    }

    public function getParentGroupType(): ?eGroupType
    {
        return $this->parentGroupType;
    }

    public function parse(DOMElement $DOMElement): static
    {
        /** @var DOMElement $childNode */
        foreach ($DOMElement->childNodes as $childNode) {
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
                case 'ToolTip':
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
                    /** @var DOMElement $grandChildNode */
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
                    /** @var DOMElement $grandChildNode */
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
        $html = '';

        // indien parent een table row betreft, dan atlijd in <td></td> plaatsen
        if ($this->getParentGroupType() === eGroupType::row) {
            $html .= "<td class='sr-table-td'>";
        }

        if ($this->getDisplayType() === eDisplayType::switch) {
            $html .= $this->getSwitchControl();
        } elseif ($this->getDisplayType() === eDisplayType::raty) {
            //$html .= $this->getToggleControl();
        } else {

            $html .= "<div class='form-group row sr-question' data-row='{$this->getQuestionID()}'>";
            $html .= "<div class='col-sm-4'>";
            $html .= "<label class='sr-label control-label align-self-center' for={$this->getName()} id='{$this->getQuestionID()}' data-id='{$this->getQuestionID()}'>{$this->getDescription()}</label>";
            $html .= '</div>';

            $html .= "<div class='col-sm-7'>";
            $html .= "<div class='sr-input-group input-group'>";

            $html .= match ($this->getDataType()) {
                eDataType::currency => "<span class='input-group-addon'>&euro;</span>",
                eDataType::date => "<span class='input-group-addon'><i class='glyphicon glyphicon-calendar'></i></span>",
                default => '',
            };

            if ($this->textValues->isNotEmpty()) { //selectbox

                if ($this->getDisplayType() === eDisplayType::toggle) {
                    $html .= $this->getToggleControl();
                } else {
                    $html .= $this->getDefaultSelectBoxControl();
                }

            } else { //input

                $html .= $this->getDefaultInputControl();
            }

            $html .= '</div>'; //input group
            $html .= '</div>'; //control

            $html .= "<div class='col-sm-1'>";
            if ($this->getHelpText() !== null) {
                $html .= "<i class='fa fa-info-circle sr-question-help' data-toggle='tooltip' data-html='true' data-placement=right auto' title='{$this->getHelpText()}'></i>";
            }
            $html .= '</div>';
            $html .= '</div>'; //form-group
        }

        if ($this->getParentGroupType() === eGroupType::row) {
            $html .= '</td>';
        }

        return $html;
    }

    public function getDefaultInputControl()
    {
        //data-styletype
        $styleType = '';
        if ($this->getCustomPropertyByName('styletype')?->getValue() !== null) {
            $styleType = "data-styletype={$this->getCustomPropertyByName('styletype')?->getValue()}";
        }

        //update
        $update = " onblur='updateControls($(this))'";
        if ($this->getUpdateUserInterface() === true) {
            $update = " onblur='updateUserInterface($(this))'";
        }

        //type depends on DataType or DisplayType
        $type = match ($this->getDataType()) {
            eDataType::date => 'type=date ',
            eDataType::time => 'type=time ',
            eDataType::integer => 'type=integer ',
            eDataType::currency => 'type=currency ',
            eDataType::decimal => 'type=decimal ',
            eDataType::string => 'type=text ',
            default => '',
        };

        if ($this->getDisplayType() === eDisplayType::password) { // overwrite $type
            $type = 'type=password ';
        }

        //format date
        $value = $this->getValue();
        if ($this->getDataType() == eDataType::date) {
            $date = date_create($value);
            $value = date_format($date, 'Y-m-d');
        }

        return
        <<<HTML
                <input {$type}
                       class="sr-question sr-question-input {$this->getStyle()->default->class}"       
                       style="{$this->getStyle()->default->inlineStyle}"
                       id="{$this->getName()}"
                       value="{$value}"
                       data-id="{$this->getQuestionID()}"
                       data-elementpath="{$this->getElementPath()}"
                       data-displaytype="{$this->getDisplayType()?->value}"
                       data-invalidmessage = "{$this->getInvalidMessage()}"
                       data-isvalid='false'
                       {$update}
                       {$styleType}
                       />
                HTML;
    }

    public function getDefaultSelectBoxControl()
    {
        //data-styletype
        $styleType = '';
        if ($this->getCustomPropertyByName('styletype')?->getValue() !== null) {
            $styleType = "data-styletype={$this->getCustomPropertyByName('styletype')?->getValue()}";
        }

        $update = " onchange='updateControls($(this))'";
            if ($this->getUpdateUserInterface() === true) {
                $update = " onchange='updateUserInterface($(this))'";
            }

        return
            <<<HTML
            <select class="sr-question sr-question-choice {$this->getStyle()->default->class}"
                    style="{$this->getStyle()->default->inlineStyle}"
                    id="{$this->getName()}"
                    name="{$this->getName()}"
                    data-id="{$this->getQuestionID()}"
                    data-elementpath="{$this->getElementPath()}"
                    data-displaytype="{$this->getDisplayType()?->value}"
                    data-invalidmessage = "{$this->getInvalidMessage()}"
                    data-isvalid='false'
                    {$update}
                    {$styleType}
                    >
                {$this->textValues->map(fn (TextValueComponentContract $textValueItem): string => $textValueItem->render($this->getValue() === $textValueItem->getValue()))->implode('')}
            </select>
            HTML;
    }

    public function getSwitchControl()
    {
        $html = '';
        $data_on = '';
        $data_off = '';
        $checked = ''; //not yet implemented

        $updateuserinterface = 'false';
        if ($this->getUpdateUserInterface()) {
            $updateuserinterface = 'true';
        }

        if ($this->textValues->count() === 2) {
            $data_on = $this->textValues->first()->getValue();
            $data_off = $this->textValues->last()->getValue();
        }

         $html .=
         <<<HTML
            <div class='sr-switch'>

            <input  type='hidden'
                    value='{$this->getValue()}'
                    name='sr_{$this->getQuestionID()}'>
            
            <label  class='switch'>

            <input  type='checkbox' 
                    id='{$this->getName()}'
                    value='{$this->getValue()}' 
                    data-elementpath="{$this->getElementPath()}"
                    data-updateinterface='{$updateuserinterface}'
                    {$checked} 
                    data-toggle='toggle' 
                    data-onvalue='{$data_on}' 
                    data-offvalue='{$data_off}' 
                    data-toggle='toggle' 
                    data-id='{$this->getQuestionID()}'
                    onchange='setSwitchValue(this);'>
            
            <span class='slider round'></span>
            </label>
            </div>    
            HTML;

            return $html;
    }

    public function getToggleControl()
    {
        //data-styletype
        $styleType = '';
        if ($this->getCustomPropertyByName('styletype')?->getValue() !== null) {
            $styleType = "data-styletype={$this->getCustomPropertyByName('styletype')?->getValue()}";
        }

        $html = '';
        $html .= "<div class='form-group sr-togglefield row'>";
        //add question
        $html .= "<input type='hidden' class='togglefield-hidden' data-id='{$this->getQuestionID()}' id='{$this->getName()}' value='{$this->getValue()}' data-styletype='{$styleType}' data-elementpath='{$this->getElementPath()}'>";
        $html .= "<div class='btn-group btn-radio'>";

        $updateuserinterface = 'false';
        if ($this->getUpdateUserInterface()) {
            $updateuserinterface = 'true';
        }

        $active = '';
        foreach($this->textValues as $textValue) {
            if ($textValue->getValue() === $this->getValue()) {
                $active = ' active';
            }
            $html .= "<button type='button' class='sr btn btn-secondary grouptooltip {$active}' data-id='{$this->getQuestionID()}' data-updateinterface='{$updateuserinterface}' data-value='{$textValue->getValue()}' data-name='{$this->getName()}' onclick='toggleClick(this)'>{$textValue->getText()}</button>";
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
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
