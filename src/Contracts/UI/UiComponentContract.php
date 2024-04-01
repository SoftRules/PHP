<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts\UI;

use DOMNode;

interface UiComponentContract
{
    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setVisibleExpression(ExpressionContract $visibleExpression): void;

    public function getVisibleExpression(): ExpressionContract;

    public function parse(DOMNode $node): static;

    public function getStyle(): object;
}
