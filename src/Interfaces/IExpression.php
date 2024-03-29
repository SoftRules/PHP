<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use DOMDocument;
use Illuminate\Support\Collection;
use SoftRules\PHP\UI\Condition;

interface IExpression
{
    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setStartValue(string $startValue): void;

    public function getStartValue(): bool;

    public function addCondition(Condition $condition): void;

    public function clean(): void;

    public function value(Collection $items, DOMDocument $userinterfaceData): bool;

    public function writeXml($writer): void;
}
