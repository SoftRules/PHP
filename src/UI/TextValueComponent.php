<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMElement;
use SoftRules\PHP\Contracts\UI\TextValueComponentContract;
use SoftRules\PHP\Traits\ParsedFromXml;

final class TextValueComponent implements TextValueComponentContract
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

    public function parse(DOMElement $DOMElement): static
    {
        if ($DOMElement->nodeName === 'Item') {
            foreach ($DOMElement->childNodes as $childNode) {
                switch ($childNode->nodeName) {
                    case 'Value':
                        $this->setValue((string) $childNode->nodeValue);
                        break;
                    case 'Text':
                        $this->setText((string) $childNode->nodeValue);
                        break;
                    case 'ImageUrl':
                        $this->setImageUrl((string) $childNode->nodeValue);
                        break;
                }
            }
        }

        return $this;
    }

    public function writeXml($writer): void
    {
        //
    }

    public function render(bool $selected): string
    {
        $selectedAttribute = $selected ? 'selected' : '';

        return
            <<<HTML
            <option value="{$this->value}" {$selectedAttribute}>{$this->text}</option>
            HTML;
    }
}
