<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use SoftRules\PHP\UI\Parameter;

interface ILabel extends ISoftRules_Base
{
    public function setLabelID($LabelID): void;

    public function getLabelID();

    public function setText($Text): void;

    public function getText();

    public function setParameter(Parameter $Parameter): void;

    public function getParameter(): Parameter;
}