<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use SoftRules\PHP\UI\Parameter;

interface IQuestion extends ISoftRules_Base
{
    public function setQuestionID($QuestionID);

    public function getQuestionID();

    public function setName($Name): void;

    public function getName();

    public function setValue($Value): void;

    public function getValue();

    public function setDescription(string $Description): void;

    public function getDescription(): string;

    public function setPlaceHolder($Placeholder): void;

    public function getPlaceHolder();

    public function setTooltip($Tooltip): void;

    public function getTooltip();

    public function setHelpText($HelpText): void;

    public function getHelpText();

    public function setDefaultState($DefaultState): void;

    public function getDefaultState();

    public function setIncludeInvisibleQuestion($IncludeInvisibleQuestion): void;

    public function getIncludeInvisibleQuestion();

    public function setDataType($DataType): void;

    public function getDisplayType();

    public function setDisplayType($DisplayType): void;

    public function setDisplayOnly($DisplayOnly): void;

    public function getDisplayOnly();

    public function getDataType();

    public function setRestrictions(IRestrictions $Restrictions): void;

    public function getRestrictions(): IRestrictions;

    public function setParameter(Parameter $Parameter): void;

    public function getParameter(): Parameter;

    public function setElementPath($ElementPath): void;

    public function getElementPath();

    public function setCoreValue($CoreValue): void;

    public function getCoreValue();

    public function setUpdateUserinterface($UpdateUserinterface): void;

    public function getUpdateUserinterface();

    public function setInvalidMessage($InvalidMessage): void;

    public function getInvalidMessage();

    public function setCustomProperties($CustomProperties): void;

    public function getCustomProperties();

    public function setDefaultStateExpression(IExpression $DefaultStateExpression): void;

    public function getDefaultStateExpression();

    public function setRequiredExpression(IExpression $RequiredExpression): void;

    public function getRequiredExpression();

    public function setValidExpression(IExpression $ValidExpression): void;

    public function getValidExpression();

    public function setUpdateExpression(IExpression $UpdateExpression): void;

    public function setReadyForProcess($ReadyForProcess): void;

    public function getReadyForProcess();

    public function setTextValues($TextValues): void;

    public function getTextValues();
}