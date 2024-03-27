<?php declare(strict_types=1);

namespace SoftRules\PHP\Enums;

enum eValueType: int
{
    case ELEMENTNAME = 0;
    case CONSTANT = 1;
    case FORMULA = 2;
}
