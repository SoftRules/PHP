<?php declare(strict_types=1);

namespace SoftRules\PHP\Enums;

enum eOperator: string
{
    case EQUAL = 'EQUAL';
    case NOT_EQUAL = 'NOT_EQUAL';
    case LESS_EQUAL = 'LESS_EQUAL';
    case GREATER_EQUAL = 'GREATER_EQUAL';
    case GREATER = 'GREATER';
    case LESS = 'LESS';
    case IN = 'IN';
}
