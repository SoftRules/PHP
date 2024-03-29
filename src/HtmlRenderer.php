<?php declare(strict_types=1);

namespace SoftRules\PHP;

use DOMDocument;
use Illuminate\Support\Collection;
use SoftRules\PHP\Enums\eButtonType;
use SoftRules\PHP\Interfaces\ISoftRules_Base;
use SoftRules\PHP\Interfaces\ItemWithCustomProperties;
use SoftRules\PHP\Interfaces\Renderable;
use SoftRules\PHP\Interfaces\RenderableWrapper;
use SoftRules\PHP\UI\Button;
use SoftRules\PHP\UI\CustomProperty;
use SoftRules\PHP\UI\Group;
use SoftRules\PHP\UI\Label;
use SoftRules\PHP\UI\Question;
use SoftRules\PHP\UI\TextValueItem;
use SoftRules\PHP\UI\UIClass;
use Stringable;

final class HtmlRenderer implements Stringable
{
    private string $html = '';
    private int $currentPage;
    public readonly int $totalPages;
    public readonly DOMDocument $userinterfaceData;
    /**
     * @var Collection<int, ISoftRules_Base>
     */
    private readonly Collection $allItems;
//    private DOMDocument $SR_XML;

    public function __construct(UIClass $UIClass)
    {
        $this->totalPages = $UIClass->getPages();
        $this->currentPage = $UIClass->getPage();
        $this->userinterfaceData = $UIClass->getUserinterfaceData();
//        $this->SR_XML = $UIClass->getSoftRulesXml();
        $this->allItems = $UIClass->items;

        $this->html .= "<p>Pagina: {$this->currentPage} v/d {$this->totalPages} Pagina's</p>";

        $this->userinterfaceItems($UIClass->items);
    }

    /**
     * @param Collection<int, ISoftRules_Base> $items
     */
    private function userinterfaceItems(Collection $items): void
    {
        foreach ($items as $item) {
            if ($item instanceof RenderableWrapper) {
                $VisibleText = '';

                if (! $this->itemVisible($item)) {
                    $VisibleText = ' Niet tonen (invisible)';
                }

                $itemName = class_basename($item);

                $this->html .= "{$itemName} Type: ";

                if ($item instanceof Group) {
                    $this->html .= '<b>' . $item->getType()->value . '</b> ' . $item->getName();
                }

                if ($item instanceof ItemWithCustomProperties) {
                    $this->html .= $this->getCustomPropertyDescriptions($item);
                }

                $this->html .= $VisibleText . '<br>';

                $this->html .= $item->renderOpeningTags();
                $this->userinterfaceItems($item->getItems());
                $this->html .= $item->renderClosingTags();

                if ($item instanceof Group && $item->shouldHavePagination()) {
                    $this->html .= "<div class='row pagination-row'>";

                    $nextPageID = $this->currentPage;
                    $previousPageID = $this->currentPage;

                    if ($this->hasPreviousPage() && $item->shouldHavePreviousPageButton()) {
                        $this->html .= $this->getPreviousButtonHtml($item->getGroupID(), $previousPageID);
                    }

                    if ($this->isLastPage()) {
                        if ($item->shouldHaveNextPageButton()) {
                            $this->html .= $this->getGoButtonHtml();
                        }
                    } elseif ($this->hasNextPage() && $item->shouldHaveNextPageButton()) {
                        $this->html .= $this->getNextButtonHtml($item->getGroupID(), $nextPageID);
                    }

                    $this->html .= '</div>';
                }
            } elseif ($item instanceof Renderable) {
                $this->html .= $item->render();
            } elseif ($item instanceof Question) {
                $id = $item->getQuestionID();
                $VisibleText = '';
                $textvalues = $this->getTextValuesDescription($item);

                if (! $this->itemVisible($item)) {
                    $VisibleText = ' Niet tonen (invisible)';
                }

                $this->html .= '<li>';
                $this->html .= "Question: {$item->getDescription()}({$item->getName()}) Value: {$item->getValue()} UpdateUserinterface: {$item->getUpdateUserinterface()}{$textvalues}{$VisibleText}";
                $this->html .= '</li>';
            } elseif ($item instanceof Label) {
                $VisibleText = '';

                if (! $this->itemVisible($item)) {
                    $VisibleText = ' Niet tonen (invisible)';
                }

                $this->html .= "Label Text: {$item->getText()}{$VisibleText}<br/>";
            } elseif ($item instanceof Button) {
                $id = $item->getButtonID();
                $StyleType = '';
                $nextPageID = $this->currentPage;
                $VisibleText = '';
                $custompropertyDescription = $this->getCustomPropertyDescriptions($item);

                if (strtolower((string) $item->getDisplayType()) === 'tile') {
                    $tile = true;
                }

                if (! $this->itemVisible($item)) {
                    $VisibleText = ' Niet tonen (invisible)';
                }

                foreach ($item->getCustomProperties() as $customproperty) {
                    switch (strtolower((string) $customproperty->getName())) {
                        case 'nextpage':
                            $name = $customproperty->getValue();
                            break;
                        case 'pictureurl':
                            $PictureUrl = $customproperty->getValue();
                            break;
                        case 'width':
                            $width = $customproperty->getValue();
                            break;
                        case 'height':
                            $height = $customproperty->getValue();
                            break;
                        case 'styletype':
                            $StyleType = " data-styleType='" . $customproperty->getValue() . "'";
                            break;
                        case 'align':
                            $Align = " data-align='" . $customproperty->getValue() . "'";
                            break;
                        default:
                            break;
                    }
                }

                $buttonfunction = match ($item->getType()) {
                    eButtonType::submit => 'processButton',
                    eButtonType::navigate, eButtonType::update => 'updateButton',
                    default => '',
                };
                $this->html .= '<li>';
                $this->html .= 'Button Text: ' . $item->getText() . $custompropertyDescription . $VisibleText . '<br>';
                $this->html .= '</li>';
                $this->html .= '<ul><li>';
                $this->html .= "<button type=\"button\" class=\"{$buttonfunction} btn btn-default\" data-type='button' data-id=" . $id . ">{$item->getText()}</button>";
                $this->html .= '</li></ul>';
            }
        }
    }

    private function getCustomPropertyDescriptions(ItemWithCustomProperties $item): string
    {
        if ($item->getCustomProperties()->isEmpty()) {
            return '';
        }

        $description = $item->getCustomProperties()
            ->implode(fn (CustomProperty $customProperty) => "{$customProperty->getName()}={$customProperty->getValue()}", ', ');

        return " {$item->getCustomProperties()->count()} Custom Properties ({$description})";
    }

    private function getTextValuesDescription(Question $item): string
    {
        if ($item->textValues->isEmpty()) {
            return '';
        }

        $items = $item->textValues
            ->implode(fn (TextValueItem $textValueItem) => "{$textValueItem->getValue()}-{$textValueItem->getText()}", ', ');

        return " Aantal TextValues: {$item->textValues->count()} ({$items})";
    }

    public function itemVisible(ISoftRules_Base $item): bool
    {
        return $item->getVisibleExpression()->value($this->allItems, $this->userinterfaceData);
    }

    private function hasPreviousPage(): bool
    {
        if ($this->totalPages < 2) {
            return false;
        }

        return $this->currentPage > 1;
    }

    private function hasNextPage(): bool
    {
        if ($this->totalPages < 2) {
            return false;
        }

        return $this->currentPage < $this->totalPages;
    }

    private function isLastPage(): bool
    {
        return $this->currentPage === $this->totalPages;
    }

    private function getNextButtonHtml(string $id, int $nextPageID): string
    {
        return "<button type='button' class='nextButton btn btn-primary sr-nextpage' data-type='Group' data-nextid='" . $nextPageID . "' data-id=" . $id . '>Volgende</button>';
    }

    private function getPreviousButtonHtml(string $id, int $previousPageID): string
    {
        return "<button type='button' class='previousButton btn btn-primary sr-previouspage' data-type='Group' data-previousid='" . $previousPageID . "' data-id=" . $id . '>Vorige</button>';
    }

    private function getGoButtonHtml(): string
    {
        return "<button type='button' class='nextButton btn btn-primary sr-nextpage' data-type='Process' id='ProcessButton' onclick='ProcessValidation()'>Volgende</button>";
    }

    public function __toString(): string
    {
        return $this->html;
    }
}
