<?php declare(strict_types=1);

namespace SoftRules\PHP\Enums;

enum eDataType: string
{
    case date = 'date';
    case time = 'time';
    case integer = 'integer';
    case int = 'int';
    case currency = 'currency';
    case decimal = 'decimal';
    case string = 'string';
    case none = '';
}
