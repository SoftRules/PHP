<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

interface IRange
{
    public function setHighValue($HighValue): void;

    public function getHighValue();

    public function setLowValue($LowValue): void;

    public function getLowValue();
}
