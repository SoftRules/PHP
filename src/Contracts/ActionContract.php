<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts;

use DOMDocument;

interface ActionContract
{
    public function setItemID(string $itemID): void;

    public function getItemID(): string;
    
    public function setCommand(string $command): void;
    
    public function getCommand(): string;

    public function setValue(string $value): void;

    public function getValue(): string;
}