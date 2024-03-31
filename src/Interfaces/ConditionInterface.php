<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use DOMDocument;
use SoftRules\PHP\Enums\eLogOperator;
use SoftRules\PHP\Enums\eOperator;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;

interface ConditionInterface
{
    public function setLogOperator(eLogOperator|string $logOperator): void;

    public function getLogOperator(): ?eLogOperator;

    public function setOperator(eOperator|string $operator): void;

    public function getOperator(): eOperator;

    public function setLeftOperand(OperandInterface $leftOperand): void;

    public function getLeftOperand(): OperandInterface;

    public function setRightOperand(OperandInterface $rightOperand): void;

    public function getRightOperand(): OperandInterface;

    public function copyFrom($sourceCondition): void;

    public function value($status, UiComponentsCollection $components, DOMDocument $UserInterfaceData);

    public function writeXml($writer): void;
}
