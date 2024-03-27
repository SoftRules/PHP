<?php declare(strict_types=1);

namespace SoftRules\PHP\Enums;

enum eState: int
{
    case New = 0;
    case Changed = 1;
    case Unchanged = 2;
}
