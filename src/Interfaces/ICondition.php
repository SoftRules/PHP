<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use DOMDocument;
use SoftRules\PHP\Enums\eLogOperator;
use SoftRules\PHP\Enums\eOperator;

interface ICondition
{
    public function setLogoperator(eLogOperator|string $logoperator): void;

    public function getLogoperator(): ?eLogOperator;

    public function setOperator(eOperator|string $operator): void;

    public function getOperator(): eOperator;

    public function setLeft_operand(IOperand $left_operand): void;

    public function getLeft_operand(): IOperand;

    public function setRight_operand(IOperand $right_operand): void;

    public function getRight_operand(): IOperand;

    public function CopyFrom($sourceCondition): void;

    public function Value($status, array $Items, DOMDocument $UserinterfaceData);

    public function WriteXml($writer): void;
}