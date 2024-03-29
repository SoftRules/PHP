<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use DOMNode;

interface BaseItemInterface
{
    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setVisibleExpression(ExpressionInterface $visibleExpression): void;

    public function getVisibleExpression(): ExpressionInterface;

    public function parse(DOMNode $node): self;
}
