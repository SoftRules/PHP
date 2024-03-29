<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

interface RangeInterface
{
    public function setHighValue($highValue): void;

    public function getHighValue();

    public function setLowValue($lowValue): void;

    public function getLowValue();
}
