<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use DOMDocument;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;

interface ExpressionInterface
{
    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setStartValue(string $startValue): void;

    public function getStartValue(): bool;

    public function addCondition(ConditionInterface $condition): void;

    public function clean(): void;

    public function value(UiComponentsCollection $components, DOMDocument $userInterfaceData): bool;

    public function writeXml($writer): void;
}
