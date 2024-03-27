<?php declare(strict_types=1);

namespace SoftRules\PHP\Enums;

enum eGroupType: string
{
    case page = 'page';
    case box = 'box';
    case expandable = 'expandable';
    case column = 'column';
    case table = 'table';
    case row = 'row';
    case popup = 'popup';
    case grid = 'grid';
    case gridcolumn = 'gridcolumn';
    case gridrow = 'gridrow';
}
