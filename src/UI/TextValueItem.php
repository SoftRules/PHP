<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use SoftRules\PHP\Interfaces\TextValueItemInterface;
use SoftRules\PHP\Traits\ParsedFromXml;

class TextValueItem implements TextValueItemInterface
{
    use ParsedFromXml;

    private string $value = '';
    private ?string $text = null;
    private ?string $imageUrl = null;

    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
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
