<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use SoftRules\PHP\Interfaces\ITextValueItem;

class TextValueItem implements ITextValueItem
{
    private $Value;
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

    public function setValue($Value): void
    {
        $this->Value = $Value;
    }

    public function getValue()
    {
        return $this->Value;
    }

    public function setImageUrl($ImageUrl): void
    {
        $this->ImageUrl = $ImageUrl;
    }

    public function getImageUrl()
    {
        return $this->ImageUrl;
    }

    public function parse($item)
    {
        switch ($item->nodeName) {
            case 'Item':
            {
                foreach ($item->childNodes as $textValueItem) {
                    switch ($textValueItem->nodeName) {
                        case 'Value':
                        {
                            $this->setValue((string) $textValueItem->nodeValue);
                            break;
                        }
                        case 'Text':
                        {
                            $this->setText((string) $textValueItem->nodeValue);
                            break;
                        }
                        case 'ImageUrl':
                        {
                            $this->setImageUrl((string) $textValueItem->nodeValue);
                            break;
                        }
                    }
                }
                break;
            }
        }
    }

    public function writeXml($writer)
    {

    }
}
