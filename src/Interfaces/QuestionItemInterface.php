<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use Illuminate\Support\Collection;

interface QuestionItemInterface extends BaseItemInterface
{
    public function setQuestionID(string $questionID);

    public function getQuestionID(): string;

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

    public function setRestrictions(RestrictionsInterface $restrictions): void;

    public function getRestrictions(): RestrictionsInterface;

    public function setParameter(ParameterInterface $parameter): void;

    public function getParameter(): ParameterInterface;

    public function setElementPath($elementPath): void;

    public function getElementPath();

    public function setCoreValue($coreValue): void;

    public function getCoreValue();

    public function setUpdateUserInterface($updateUserInterface): void;

    public function getUpdateUserInterface();

    public function setInvalidMessage($invalidMessage): void;

    public function getInvalidMessage();

    public function addCustomProperty(CustomPropertyInterface $customProperty): void;

    /**
     * @return Collection<int, CustomPropertyInterface>
     */
    public function getCustomProperties(): Collection;

    public function setDefaultStateExpression(ExpressionInterface $defaultStateExpression): void;

    public function getDefaultStateExpression(): ExpressionInterface;

    public function setRequiredExpression(ExpressionInterface $requiredExpression): void;

    public function getRequiredExpression(): ExpressionInterface;

    public function setValidExpression(ExpressionInterface $validExpression): void;

    public function getValidExpression(): ExpressionInterface;

    public function setUpdateExpression(ExpressionInterface $updateExpression): void;

    public function setReadyForProcess($readyForProcess): void;

    public function getReadyForProcess();

    public function addTextValue(TextValueItemInterface $textValue): void;
}
