<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Components;

use Carbon\Carbon;
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
use SoftRules\PHP\Enums\eScope;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;
use SoftRules\PHP\UI\CustomProperty;
use SoftRules\PHP\UI\Expression;
use SoftRules\PHP\UI\Parameter;
use SoftRules\PHP\UI\Restrictions;
use SoftRules\PHP\UI\Style\QuestionComponentStyle;
use SoftRules\PHP\UI\TextValueComponent;

final class Question implements ComponentWithCustomPropertiesContract, QuestionComponentContract, Renderable
{
    use HasCustomProperties;
    use ParsedFromXml;

    public static ?QuestionComponentStyle $style = null;

    private string $questionID;

    private string $key;

    private $name;

    private $value;

    private string $description;

    private $placeholder;

    private $tooltip;

    private $helpText;

    private eDefaultState $defaultState = eDefaultState::none;

    private $includeInvisibleQuestion;

    private eDataType $dataType = eDataType::none;

    private eDisplayType $displayType = eDisplayType::none;

    private $displayOnly;

    private RestrictionsContract $restrictions;

    private ParameterContract $parameter;

    private $elementPath;

    private bool $updateUserInterface = false;

    private eScope $scope = eScope::Userinterface;

    private array $groupIDs = [];

    private bool $showWaitScreen = true;

    private string $invalidMessage = '';

    private ?bool $readyForProcess = null;

    private $coreValue;

    private eGroupType $parentGroupType = eGroupType::none;

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

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getKey(): string
    {
        return $this->key;
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
            $this->updateUserInterface = strtolower($updateUserInterface) === 'true';
        }
    }

    public function getUpdateUserInterface(): bool
    {
        return $this->updateUserInterface;
    }

    public function setScope($scope): void
    {
        $this->scope = eScope::from(strtolower((string) $scope));
    }

    public function getScope(): eScope
    {
        return $this->scope;
    }

    public function getGroupdIDs(): array
    {
        return $this->groupIDs;
    }

    public function addGroupID($groupid): void
    {
        $this->groupIDs[] = $groupid;
    }

    public function getShowWaitScreen(): bool
    {
        return $this->showWaitScreen;
    }

    public function setShowWaitScreen(bool $showWaitScreen): void
    {
        $this->showWaitScreen = $showWaitScreen;
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
        if (is_string($readyForProcess)) {
            $this->readyForProcess = strtolower($readyForProcess) === 'true';
        }
    }

    public function getReadyForProcess(): bool
    {
        return $this->readyForProcess ?? false;
    }

    public function addTextValue(TextValueComponentContract $textValue): void
    {
        $this->textValues->add($textValue);
    }

    public function setParentGroupType(eGroupType $parentGroupType): void
    {
        $this->parentGroupType = $parentGroupType;
    }

    public function getParentGroupType(): eGroupType
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
                case 'Key':
                    $this->setKey($childNode->nodeValue);
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
                    if ($childNode->attributes->getNamedItem('Scope') !== null) {
                        $this->setScope($childNode->attributes->getNamedItem('Scope')->nodeValue);
                    }

                    if ($childNode->attributes->getNamedItem('ShowWaitScreen') !== null) {
                        $this->setShowWaitScreen((bool) $childNode->attributes->getNamedItem('ShowWaitScreen')->nodeValue);
                    }

                    break;
                case 'UpdateGroups':
                        /** @var DOMElement $grandChildNode */
                        foreach ($childNode->childNodes as $grandChildNode) {
                            $this->addGroupID($grandChildNode->nodeValue);
                        }

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

    public function render($components, $userInterfaceData): string
    {
        $html = '';

        $visible = $this->visibleExpression->value($components, $userInterfaceData);
        $visibleStyle = $visible ? '' : 'display: none;';

        if ($this->defaultState === eDefaultState::Editable) {
            $enable = $this->defaultStateExpression->value($components, $userInterfaceData);
        } else {
            $enable = ! $this->defaultStateExpression->value($components, $userInterfaceData);
        }

        $disabled = $enable ? '' : 'disabled';

        $viewLabel = $this->getCustomPropertyByName('nolabel')?->getValue() ?? true;
        if ($viewLabel === '1') {
            $viewLabel = true;
        } elseif ($viewLabel === '0') {
            $viewLabel = false;
        }

        // indien parent een table row betreft, dan atlijd in <td></td> plaatsen
        if ($this->parentGroupType === eGroupType::row) {
            $html .= "<td class='sr-table-td'>";
            $viewLabel = false; // in tables never paint question labels
        }

        if ($this->displayType === eDisplayType::switch) {
            $html .= $this->getSwitchControl($disabled);
        } elseif ($this->displayType === eDisplayType::raty) {
            // $html .= $this->getToggleControl();
        } else {
            $html .= "<div class='form-group row sr-question' style='{$visibleStyle}' id='{$this->questionID}-row'>";
            if ($viewLabel) {
                $html .= "<div class='col-sm-4'>";
                $html .= "<label class='sr-label control-label align-self-center' for='{$this->questionID}' id='{$this->questionID}-label'>{$this->description}</label>";
                $html .= '</div>';
            }

            $html .= "<div class='col-sm-7'>";
            $html .= "<div class='sr-input-group input-group'>";

            $html .= match ($this->dataType) {
                eDataType::currency => "<span class='input-group-addon'>&euro;</span>",
                default => '',
            };

            if ($this->displayType === eDisplayType::slider) {
                $html .= $this->getSliderControl();
            } elseif ($this->textValues->isNotEmpty()) { // selectbox
                if ($this->displayType === eDisplayType::toggle) {
                    $html .= $this->getToggleControl();
                } else {
                    $html .= $this->getDefaultSelectBoxControl($disabled);
                }
            } else { // input
                $html .= $this->getDefaultInputControl($disabled);
            }

            $html .= '</div>'; // input group
            $html .= '</div>'; // control

            $html .= "<div class='col-sm-1'>";
            if ($this->helpText !== null) {
                $html .= "<i class='fa fa-info-circle sr-question-help sr-tooltip' data-tippy-content='{$this->helpText}'></i>";
            }

            $html .= '</div>';
            $html .= '</div>'; // form-group
        }

        if ($this->parentGroupType === eGroupType::row) {
            $html .= '</td>';
        }

        return $html;
    }

    private function getDefaultInputControl(string $disabled): string
    {
        // update
        $update = " onblur='updateControls($(this))'";
        if ($this->updateUserInterface) {

            $wait = $this->showWaitScreen;

            $update = " onblur='updateUserInterface($(this" . $wait . "))'";

            if ($this->scope === eScope::Question) {
                $update = " onblur='UpdateQuestion($(this" . $wait . "))'";
            } elseif ($this->scope === eScope::Group) {
                $update = " onblur='updateGroup($(this" . $wait . "))'";
            }

            $update = " onblur='updateUserInterface($(this))'";
        }

        // type depends on DataType or DisplayType
        $type = match ($this->dataType) {
            eDataType::date => 'type="date" ',
            eDataType::time => 'type="time" ',
            eDataType::integer, eDataType::int, eDataType::decimal => 'type="number" ',
            eDataType::currency, eDataType::string => 'type="text" ',
            default => '',
        };

        if ($this->displayType === eDisplayType::password) { // overwrite $type
            $type = 'type="password" ';
        }

        // format date
        $value = $this->value;
        if ($this->dataType === eDataType::date && $value !== '') {
            $value = Carbon::parse($value)->format('Y-m-d');
        }

        // Custom property Styletype
        $styleType = $this->styleTypeProperty();

        return
            <<<HTML
                <input {$type}
                       data-type="{$this->dataType->value}"
                       class="sr-question sr-question-input {$this->getStyle()->default->class}"
                       style="{$this->getStyle()->default->inlineStyle}"
                       {$styleType}
                       name="{$this->name}"
                       id="{$this->questionID}"
                       value="{$value}"
                       data-value="{$value}"
                       data-id="{$this->questionID}"
                       data-elementpath="{$this->elementPath}"
                       data-displaytype="{$this->displayType->value}"
                       data-invalidmessage="{$this->invalidMessage}"
                       data-isvalid='true'
                       data-length="{$this->restrictions->getLength()}"
                       data-pattern="{$this->restrictions->getPattern()}"
                       {$update}
                       {$disabled}
                       />
                HTML;
    }

    private function commaToDotNotation(string $value): string
    {
        return str_replace(',', '.', $value);
    }

    private function getSliderControl(): string
    {
        $updateMethod = $this->updateUserInterface ? 'updateUserInterface' : 'updateControls';

        // format date
        $value = $this->commaToDotNotation($this->value);

        $step = $this->commaToDotNotation($this->getCustomPropertyByName('Stepsize')?->getValue() ?? '1');
        $min = $this->commaToDotNotation($this->getCustomPropertyByName('MinValue')?->getValue() ?? '1');
        $max = $this->commaToDotNotation($this->getCustomPropertyByName('MaxValue')?->getValue() ?? '1');

        $styleType = $this->styleTypeProperty();

        return
            <<<HTML
                <input type="hidden"
                       class="sr-question sr-question-slider sr-slider {$this->getStyle()->slider->class}"
                       style="{$this->getStyle()->slider->inlineStyle}"
                       {$styleType}
                       name="{$this->name}"
                       id="{$this->questionID}"
                       value="{$value}"
                       data-value="{$value}"
                       data-prevValue="{$value}"
                       data-id="{$this->questionID}"
                       data-elementpath="{$this->elementPath}"
                       data-displaytype="{$this->displayType->value}"
                       data-invalidmessage="{$this->invalidMessage}"
                       data-isvalid='true'/>

                <script>
                    new rSlider({
                        target: '#{$this->questionID}',
                        step: {$step},
                        values: {
                            min: {$min},
                            max: {$max},
                        },
                        set: [{$value}],
                        scale: false,
                        labels: false,
                        onChange() {
                            {$updateMethod}($('#{$this->questionID}[data-id="{$this->questionID}"]'));
                        },
                    });
                </script>
                HTML;
    }

    private function getDefaultSelectBoxControl(string $disabled): string
    {
        $update = " onchange='updateControls($(this))'";
        if ($this->updateUserInterface) {
            $update = " onchange='updateUserInterface($(this))'";
        }

        // Custom property Styletype
        $styleType = $this->styleTypeProperty();

        return
            <<<HTML
            <select class="sr-question sr-question-choice {$this->getStyle()->default->class}"
                    style="{$this->getStyle()->default->inlineStyle}"
                    {$styleType}
                    id="{$this->questionID}"
                    name="{$this->name}"
                    data-id="{$this->questionID}"
                    data-elementpath="{$this->elementPath}"
                    data-displaytype="{$this->displayType->value}"
                    data-invalidmessage = "{$this->invalidMessage}"
                    {$update}
                    {$disabled}

                    data-isvalid='false'>
                {$this->textValues->map(fn (TextValueComponentContract $textValueItem): string => $textValueItem->render($this->value === $textValueItem->getValue()))->implode('')}
            </select>
            HTML;
    }

    private function getSwitchControl(string $disabled): string
    {
        $data_on = '1';
        $data_off = '0';

        $updateuserinterface = $this->updateUserInterface ? 'true' : 'false';

        if ($this->textValues->count() === 2) {
            $data_on = $this->textValues->first()->getValue();
            $data_off = $this->textValues->last()->getValue();
        }

        $checked = $this->value === $data_on ? 'checked' : '';

        return
            <<<HTML
            <span class="onoffswitch">
                <label class="switch-label">
                    <input type='checkbox'
                            id="{$this->questionID}"
                            name='{$this->name}'
                            value='{$data_on}'
                            data-elementpath="{$this->elementPath}"
                            data-updateinterface='{$updateuserinterface}'
                            {$checked}
                            data-onvalue='{$data_on}'
                            data-offvalue='{$data_off}'
                            data-id='{$this->questionID}'
                            data-toggle='toggle'
                            {$disabled}
                            onchange='setSwitchValue(this);'/>

                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </span>
            HTML;
    }

    private function getToggleControl(): string
    {
        $updateuserinterface = $this->updateUserInterface ? 'true' : 'false';

        // Custom property Styletype
        $styleType = $this->styleTypeProperty();

        $values = '';
        foreach ($this->textValues as $textValue) {
            $active = $textValue->getValue() === $this->value ? 'active' : '';

            $values .= <<<HTML
            <button type='button'
                    class='sr btn btn-secondary {$active}'
                    {$styleType}
                    data-id='{$this->questionID}'
                    data-updateinterface='{$updateuserinterface}'
                    data-value='{$textValue->getValue()}'
                    onclick='toggleClick(this)'>
                {$textValue->getText()}
            </button>
            HTML;
        }

        return
            <<<HTML
            <div class='form-group sr-togglefield row'>
                <input type='hidden'
                       class='togglefield-hidden'
                       data-id='{$this->questionID}'
                       id='{$this->questionID}'
                       value='{$this->value}'
                       name='{$this->name}'
                       {$styleType}
                       data-elementpath='{$this->elementPath}'/>

                {$values}
                </div>
            HTML;
    }
}
