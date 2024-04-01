<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts\UI;

interface RangeContract
{
    public function setHighValue($highValue): void;

    public function getHighValue();

    public function setLowValue($lowValue): void;

    public function getLowValue();
}
