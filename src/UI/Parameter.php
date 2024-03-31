<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use SoftRules\PHP\Interfaces\ParameterInterface;
use SoftRules\PHP\Traits\ParsedFromXml;

class Parameter implements ParameterInterface
{
    use ParsedFromXml;

    private ?string $name = null;

    private $path;

    private ?string $parentGroupID = null;

    private ?string $parentUserInterfaceID = null;

    private ?string $parentConfigID = null;

    private $renew;

    private $usedByEvents;

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setPath($path): void
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setParentGroupID(?string $parentGroupID): void
    {
        $this->parentGroupID = $parentGroupID;
    }

    public function getParentGroupID(): ?string
    {
        return $this->parentGroupID;
    }

    public function setParentUserInterfaceID(?string $parentUserInterfaceID): void
    {
        $this->parentUserInterfaceID = $parentUserInterfaceID;
    }

    public function getParentUserInterfaceID(): ?string
    {
        return $this->parentUserInterfaceID;
    }

    public function setParentConfigID(?string $parentConfigID): void
    {
        $this->parentConfigID = $parentConfigID;
    }

    public function getParentConfigID(): ?string
    {
        return $this->parentConfigID;
    }

    public function setRenew($renew): void
    {
        $this->renew = $renew;
    }

    public function getRenew()
    {
        return $this->renew;
    }

    public function setUsedByEvents($usedByEvents): void
    {
        $this->usedByEvents = $usedByEvents;
    }

    public function getUsedByEvents()
    {
        return $this->usedByEvents;
    }

    public function parse(DOMNode $node): static
    {
        foreach ($node->childNodes as $parameter) {
            switch ($parameter->nodeName) {
                case 'Name':
                    $this->setName($parameter->nodeValue);
                    break;
                case 'Path':
                    $this->setPath($parameter->nodeValue);
                    break;
                case 'ParentGroupID':
                    $this->setParentGroupID($parameter->nodeValue);
                    break;
                case 'ParentUserinterfaceID':
                    $this->setParentUserInterfaceID($parameter->nodeValue);
                    break;
                case 'ParentConfigID':
                    $this->setParentConfigID($parameter->nodeValue);
                    break;
                case 'Renew':
                    $this->setRenew($parameter->nodeValue);
                    break;
                case 'UsedByEvents':
                    $this->setUsedByEvents($parameter->nodeValue);
                    break;
            }
        }

        return $this;
    }
}
