<?php declare(strict_types=1);

namespace SoftRules\PHP\Enums;

enum eDisplayType: string
{
    case slider = 'slider';
    case radiobutton = 'radiobutton';
    case multiline = 'multiline';
    case switch = 'switch';
    case checkbox = 'checkbox';
    case informationbutton = 'informationbutton';
    case kenteken = 'kenteken';
    case attention = 'attention';
    case raty = 'raty';
    case tile = 'tile';
    case toggle = 'toggle';
    case password = 'password';
    case daterange = 'daterange';
    case iban = 'iban';
    case image = 'image';
    case notificationerror = 'notificationerror';
    case none = '';
}
