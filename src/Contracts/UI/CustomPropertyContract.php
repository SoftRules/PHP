<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts\UI;

use DOMElement;

interface CustomPropertyContract
{
    public function setName(?string $name): void;

    public function getName(): ?string;

    public function setValue(string|bool|null $value): void;

    public function getValue(): string|bool|null;

    public function isTrue(): bool;

    public function parse(DOMElement $DOMElement): static;
}
