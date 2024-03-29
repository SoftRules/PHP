<?php declare(strict_types=1);

namespace SoftRules\PHP\Traits;

use DOMNode;
use SoftRules\PHP\Interfaces\BaseItemInterface;

/**
 * @mixin BaseItemInterface
 */
trait ParsedFromXml
{
    public static function createFromDomNode(DOMNode $DOMNode): self
    {
        return (new self())->parse($DOMNode);
    }
}
