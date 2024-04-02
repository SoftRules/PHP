<?php declare(strict_types=1);

namespace SoftRules\PHP\Traits;

use DOMElement;
use SoftRules\PHP\Contracts\UI\UiComponentContract;

/**
 * @mixin UiComponentContract
 */
trait ParsedFromXml
{
    public static function createFromDomNode(DOMElement $DOMElement): self
    {
        return (new self())->parse($DOMElement);
    }
}
