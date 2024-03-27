<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use DOMDocument;

interface IExpression
{
    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setStartValue(string $startValue): void;

    public function getStartValue(): bool;

    public function setConditions(array $conditions): void;

    public function getConditions(): array;

    public function Clean(): void;

    public function Value(array $Items, DOMDocument $UserinterfaceData): bool;

    public function WriteXml($writer): void;
}