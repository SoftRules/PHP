<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMElement;
use SoftRules\PHP\Contracts\UI\ParameterContract;
use SoftRules\PHP\Traits\ParsedFromXml;

class Parameter implements ParameterContract
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

    public function parse(DOMElement $DOMElement): static
    {
        /** @var DOMElement $childNode */
        foreach ($DOMElement->childNodes as $childNode) {
            switch ($childNode->nodeName) {
                case 'Name':
                    $this->setName($childNode->nodeValue);
                    break;
                case 'Path':
                    $this->setPath($childNode->nodeValue);
                    break;
                case 'ParentGroupID':
                    $this->setParentGroupID($childNode->nodeValue);
                    break;
                case 'ParentUserinterfaceID':
                    $this->setParentUserInterfaceID($childNode->nodeValue);
                    break;
                case 'ParentConfigID':
                    $this->setParentConfigID($childNode->nodeValue);
                    break;
                case 'Renew':
                    $this->setRenew($childNode->nodeValue);
                    break;
                case 'UsedByEvents':
                    $this->setUsedByEvents($childNode->nodeValue);
                    break;
            }
        }

        return $this;
    }
}
