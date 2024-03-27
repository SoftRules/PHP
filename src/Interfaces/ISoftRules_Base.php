<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

interface ISoftRules_Base
{
    public function setDescription(string $Description): void;

    public function getDescription(): string;

    public function setVisibleExpression(IExpression $VisibleExpression): void;

    public function getVisibleExpression(): IExpression;
}