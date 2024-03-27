<?php declare(strict_types=1);

namespace SoftRules\PHP\Enums;

enum eButtonType: string
{
    case submit = 'submit';
    case update = 'update';
    case navigate = 'navigate';
    case none = 'none';
}
