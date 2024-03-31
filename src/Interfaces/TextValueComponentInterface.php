<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

interface TextValueComponentInterface
{
    public function setValue(string $value): void;

    public function getValue(): string;

    public function setText(?string $text): void;

    public function getText(): ?string;

    public function getImageUrl(): ?string;

    public function setImageUrl(?string $imageUrl): void;

    public function writeXml($writer);
}
