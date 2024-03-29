<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use SoftRules\PHP\Interfaces\IParameter;
use SoftRules\PHP\Traits\ParsedFromXml;

class Parameter implements IParameter
{
    use ParsedFromXml;

    private $Name;
    private $Path;
    private $ParentGroupID;
    private $ParentUserinterfaceID;
    private $ParentConfigID;
    private $Renew;
    private $UsedByEvents;

    public function setName($Name): void
    {
        $this->Name = $Name;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setPath($Path): void
    {
        $this->Path = $Path;
    }

    public function getPath()
    {
        return $this->Path;
    }

    public function setParentGroupID($ParentGroupID): void
    {
        $this->ParentGroupID = $ParentGroupID;
    }

    public function getParentGroupID()
    {
        return $this->ParentGroupID;
    }

    public function setParentUserinterfaceID($ParentUserinterfaceID): void
    {
        $this->ParentUserinterfaceID = $ParentUserinterfaceID;
    }

    public function getParentUserinterfaceID()
    {
        return $this->ParentUserinterfaceID;
    }

    public function setParentConfigID($ParentConfigID): void
    {
        $this->ParentConfigID = $ParentConfigID;
    }

    public function getParentConfigID()
    {
        return $this->ParentConfigID;
    }

    public function setRenew($Renew): void
    {
        $this->Renew = $Renew;
    }

    public function getRenew()
    {
        return $this->Renew;
    }

    public function setUsedByEvents($UsedByEvents): void
    {
        $this->UsedByEvents = $UsedByEvents;
    }

    public function getUsedByEvents()
    {
        return $this->UsedByEvents;
    }

    public function parse(DOMNode $item): self
    {
        foreach ($item->childNodes as $parameter) {
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
                    $this->setParentUserinterfaceID($parameter->nodeValue);
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
