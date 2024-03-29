<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use Illuminate\Support\Collection;
use SoftRules\PHP\UI\CustomProperty;
use SoftRules\PHP\UI\Parameter;
use SoftRules\PHP\UI\TextValueItem;

interface IQuestion extends ISoftRules_Base
{
    public function setQuestionID($questionID);

    public function getQuestionID();

    public function setName($name): void;

    public function getName();

    public function setValue($value): void;

    public function getValue();

    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setPlaceHolder($placeholder): void;

    public function getPlaceHolder();

    public function setTooltip($tooltip): void;

    public function getTooltip();

    public function setHelpText($helpText): void;

    public function getHelpText();

    public function setDefaultState($defaultState): void;

    public function getDefaultState();

    public function setIncludeInvisibleQuestion($includeInvisibleQuestion): void;

    public function getIncludeInvisibleQuestion();

    public function setDataType($dataType): void;

    public function getDisplayType();

    public function setDisplayType($displayType): void;

    public function setDisplayOnly($displayOnly): void;

    public function getDisplayOnly();

    public function getDataType();

    public function setRestrictions(IRestrictions $restrictions): void;

    public function getRestrictions(): IRestrictions;

    public function setParameter(Parameter $parameter): void;

    public function getParameter(): Parameter;

    public function setElementPath($elementPath): void;

    public function getElementPath();

    public function setCoreValue($coreValue): void;

    public function getCoreValue();

    public function setUpdateUserinterface($updateUserinterface): void;

    public function getUpdateUserinterface();

    public function setInvalidMessage($invalidMessage): void;

    public function getInvalidMessage();

    public function addCustomProperty(CustomProperty $customProperty): void;

    public function getCustomProperties(): Collection;

    public function setDefaultStateExpression(IExpression $defaultStateExpression): void;

    public function getDefaultStateExpression();

    public function setRequiredExpression(IExpression $requiredExpression): void;

    public function getRequiredExpression();

    public function setValidExpression(IExpression $validExpression): void;

    public function getValidExpression();

    public function setUpdateExpression(IExpression $updateExpression): void;

    public function setReadyForProcess($readyForProcess): void;

    public function getReadyForProcess();

    public function addTextValue(TextValueItem $textValue): void;
}
