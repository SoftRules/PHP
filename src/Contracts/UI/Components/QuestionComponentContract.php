<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts\UI\Components;

use Illuminate\Support\Collection;
use SoftRules\PHP\Contracts\UI\CustomPropertyContract;
use SoftRules\PHP\Contracts\UI\ExpressionContract;
use SoftRules\PHP\Contracts\UI\ParameterContract;
use SoftRules\PHP\Contracts\UI\RestrictionsContract;
use SoftRules\PHP\Contracts\UI\TextValueComponentContract;
use SoftRules\PHP\Contracts\UI\UiComponentContract;
use SoftRules\PHP\Enums\eDataType;
use SoftRules\PHP\Enums\eDefaultState;
use SoftRules\PHP\Enums\eDisplayType;

interface QuestionComponentContract extends UiComponentContract
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

    public function setDefaultState(eDefaultState|string $defaultState): void;

    public function getDefaultState();

    public function setIncludeInvisibleQuestion($includeInvisibleQuestion): void;

    public function getIncludeInvisibleQuestion();

    public function setDataType(eDataType|string $dataType): void;

    public function getDisplayType();

    public function setDisplayType(eDisplayType|string $displayType): void;

    public function setDisplayOnly($displayOnly): void;

    public function getDisplayOnly();

    public function getDataType();

    public function setRestrictions(RestrictionsContract $restrictions): void;

    public function getRestrictions(): RestrictionsContract;

    public function setParameter(ParameterContract $parameter): void;

    public function getParameter(): ParameterContract;

    public function setElementPath($elementPath): void;

    public function getElementPath();

    public function setCoreValue($coreValue): void;

    public function getCoreValue();

    public function setUpdateUserInterface($updateUserInterface): void;

    public function getUpdateUserInterface();

    public function setInvalidMessage($invalidMessage): void;

    public function getInvalidMessage(): string;

    public function addCustomProperty(CustomPropertyContract $customProperty): void;

    /**
     * @return Collection<int, CustomPropertyContract>
     */
    public function getCustomProperties(): Collection;

    public function setDefaultStateExpression(ExpressionContract $defaultStateExpression): void;

    public function getDefaultStateExpression(): ExpressionContract;

    public function setRequiredExpression(ExpressionContract $requiredExpression): void;

    public function getRequiredExpression(): ExpressionContract;

    public function setValidExpression(ExpressionContract $validExpression): void;

    public function getValidExpression(): ExpressionContract;

    public function setUpdateExpression(ExpressionContract $updateExpression): void;

    public function setReadyForProcess($readyForProcess): void;

    public function getReadyForProcess();

    public function addTextValue(TextValueComponentContract $textValue): void;
}
