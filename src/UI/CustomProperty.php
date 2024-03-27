<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use SoftRules\PHP\Interfaces\ICustomProperty;

class CustomProperty implements ICustomProperty
{
    private $Name;
    private $Value;

    public function setName($Name): void
    {
        $this->Name = $Name;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setValue($Value): void
    {
        $this->Value = $Value;
    }

    public function getValue()
    {
        return $this->Value;
    }

    public function parse($item)
    {
        foreach ($item->childNodes as $values) {
            switch ($values->nodeName) {
                case 'Name':
                    $this->setName($values->nodeValue);
                    break;
                case 'Value':
                    $this->setValue($values->nodeValue);
                    break;
            }
        }
    }
}
