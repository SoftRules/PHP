<?php declare(strict_types=1);

namespace SoftRules\PHP\Enums;

enum eElementType: int
{
    case None = 0;
    case AlphaNumeric = 1;
    case Integer = 2;
    case FloatingPoint = 3;
    case Currency = 4;
    case Date = 5;
    case Time = 6;
    case Array = 7;
    case String = 8;
}
