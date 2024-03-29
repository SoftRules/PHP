<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use DOMNode;

interface ISoftRules_Base
{
    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setVisibleExpression(IExpression $visibleExpression): void;

    public function getVisibleExpression(): IExpression;

    public function parse(DOMNode $node): self;
}
