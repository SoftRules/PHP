<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DOMDocument;
use DOMElement;
use DOMXPath;
use SoftRules\PHP\Contracts\ActionContract;
use SoftRules\PHP\Contracts\UI\ConditionContract;
use SoftRules\PHP\Contracts\UI\OperandContract;
use SoftRules\PHP\Enums\eLogOperator;
use SoftRules\PHP\Enums\eOperator;
use SoftRules\PHP\Enums\eValueType;
use SoftRules\PHP\Traits\ParsedFromXml;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;
use SoftRules\PHP\UI\Components\Group;
use SoftRules\PHP\UI\Components\Question;
use Throwable;

class Action implements ActionContract
{ 
    private string $itemID;
    private string $command;
    private string $value;

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

    public function __construct(string $itemID, string $command, string $value)
    {
        $this->itemID = $itemID;
        $this->command = $command;
        $this->value = $value;
    }
}