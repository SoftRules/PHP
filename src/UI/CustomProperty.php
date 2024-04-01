<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use SoftRules\PHP\Contracts\UI\CustomPropertyContract;
use SoftRules\PHP\Traits\ParsedFromXml;

class CustomProperty implements CustomPropertyContract
{
    use ParsedFromXml;

    private ?string $name = null;

    private string|bool|null $value = null;

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setValue(string|bool|null $value): void
    {
        $this->value = $value;
    }

    public function getValue(): string|bool|null
    {
        return $this->value;
    }

    public function isTrue(): bool
    {
        if ($this->value === null) {
            return false;
        }

        if (is_bool($this->value)) {
            return $this->value;
        }

        return strtolower($this->value) === 'true';
    }

    public function parse(DOMNode $node): static
    {
        foreach ($node->childNodes as $values) {
            switch ($values->nodeName) {
                case 'Name':
                    $this->setName($values->nodeValue);
                    break;
                case 'Value':
                    $this->setValue($values->nodeValue);
                    break;
            }
        }

        return $this;
    }
}
