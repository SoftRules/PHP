<?php declare(strict_types=1);

namespace SoftRules\PHP\Enums;

enum eDefaultState: int
{
    case Editable = 0;
    case Readonly = 1;
}
