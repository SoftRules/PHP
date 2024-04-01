<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts\UI;

use DOMDocument;
use SoftRules\PHP\Enums\eLogOperator;
use SoftRules\PHP\Enums\eOperator;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;

interface ConditionContract
{
    public function setLogOperator(eLogOperator|string $logOperator): void;

    public function getLogOperator(): ?eLogOperator;

    public function setOperator(eOperator|string $operator): void;

    public function getOperator(): eOperator;

    public function setLeftOperand(OperandContract $leftOperand): void;

    public function getLeftOperand(): OperandContract;

    public function setRightOperand(OperandContract $rightOperand): void;

    public function getRightOperand(): OperandContract;

    public function copyFrom($sourceCondition): void;

    public function value($status, UiComponentsCollection $components, DOMDocument $UserInterfaceData);

    public function writeXml($writer): void;
}
