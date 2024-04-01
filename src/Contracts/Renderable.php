<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts;

interface Renderable
{
    public function render(): string;
}
