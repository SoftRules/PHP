<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use DOMNode;

interface CustomPropertyInterface
{
    public function setName(?string $name): void;

    public function getName(): ?string;

    public function setValue(string|bool|null $value): void;

    public function getValue(): string|bool|null;

    public function isTrue(): bool;

    public function parse(DOMNode $node): static;
}
