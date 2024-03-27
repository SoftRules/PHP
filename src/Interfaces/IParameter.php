<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

interface IParameter
{
    public function setName($Name): void;

    public function getName();

    public function setPath($Path): void;

    public function getPath();

    public function setParentGroupID($ParentGroupID): void;

    public function getParentGroupID();

    public function setParentUserinterfaceID($ParentUserinterfaceID): void;

    public function getParentUserinterfaceID();

    public function getParentConfigID();

    public function setParentConfigID($ParentConfigID): void;

    public function setUsedByEvents($UsedByEvents): void;

    public function getUsedByEvents();
}