<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts\UI;

use DOMElement;

interface UiComponentContract
{
    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setVisibleExpression(ExpressionContract $visibleExpression): void;

    public function getVisibleExpression(): ExpressionContract;

    public function parse(DOMElement $DOMElement): static;

    public function getStyle(): object;
}
