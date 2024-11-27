<?php declare(strict_types=1);

namespace SoftRules\PHP\Enums;

enum eScope: string
{
    case Userinterface = 'userinterface';
    case Group = 'group';
    case Question = 'question';
}