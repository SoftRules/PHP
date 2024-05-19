<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use SoftRules\PHP\Contracts\ActionContract;

class Action implements ActionContract
{
    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function setItemID(string $itemID): void
    {
        $this->itemID = $itemID;
    }

    public function getItemID(): string
    {
        return $this->itemID;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __construct(private string $itemID, private string $command, private string $value)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'ItemID' => $this->itemID,
            'Command' => $this->command,
            'Value' => $this->value,
        ];
    } // jsonSerialize
}
