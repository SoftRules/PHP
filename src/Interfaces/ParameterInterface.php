<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

interface ParameterInterface
{
    public function setName(string $name): void;

    public function getName(): ?string;

    public function setPath($path): void;

    public function getPath();

    public function setParentGroupID(?string  $parentGroupID): void;

    public function getParentGroupID(): ?string;

    public function setParentUserInterfaceID(?string  $parentUserInterfaceID): void;

    public function getParentUserInterfaceID(): ?string;

    public function getParentConfigID(): ?string;

    public function setParentConfigID(?string  $parentConfigID): void;

    public function setUsedByEvents($usedByEvents): void;

    public function getUsedByEvents();
}
