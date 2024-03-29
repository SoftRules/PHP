<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use SoftRules\PHP\Interfaces\ITextValueItem;
use SoftRules\PHP\Traits\ParsedFromXml;

class TextValueItem implements ITextValueItem
{
    use ParsedFromXml;

    private $value;
    private $Text;
    private $ImageUrl;

    public function setText($Text): void
    {
        $this->Text = $Text;
    }

    public function getText()
    {
        return $this->Text;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setImageUrl($ImageUrl): void
    {
        $this->ImageUrl = $ImageUrl;
    }

    public function getImageUrl()
    {
        return $this->ImageUrl;
    }

    public function parse(DOMNode $node): self
    {
        switch ($node->nodeName) {
            case 'Item':
                foreach ($node->childNodes as $textValueItem) {
                    switch ($textValueItem->nodeName) {
                        case 'Value':
                            $this->setValue((string) $textValueItem->nodeValue);
                            break;
                        case 'Text':
                            $this->setText((string) $textValueItem->nodeValue);
                            break;
                        case 'ImageUrl':
                            $this->setImageUrl((string) $textValueItem->nodeValue);
                            break;
                    }
                }
                break;
        }

        return $this;
    }

    public function writeXml($writer): void
    {
        //
    }
}
