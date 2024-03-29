<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use SoftRules\PHP\UI\Parameter;

interface ILabel extends ISoftRules_Base
{
    public function setLabelID(string $labelID): void;

    public function getLabelID(): string;

    public function setText(string $text): void;

    public function getText(): string;

    public function setParameter(Parameter $parameter): void;

    public function getParameter(): Parameter;
}
