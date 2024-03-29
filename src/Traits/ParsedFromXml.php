<?php declare(strict_types=1);

namespace SoftRules\PHP\Traits;

use DOMNode;
use SoftRules\PHP\Interfaces\ISoftRules_Base;

/**
 * @mixin ISoftRules_Base
 */
trait ParsedFromXml
{
    public static function createFromDomNode(DOMNode $DOMNode): self
    {
        return (new self())->parse($DOMNode);
    }
}
