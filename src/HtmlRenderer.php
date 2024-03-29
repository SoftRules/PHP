<?php declare(strict_types=1);

namespace SoftRules\PHP;

use DOMDocument;
use Illuminate\Support\Collection;
use SoftRules\PHP\Interfaces\BaseItemInterface;
use SoftRules\PHP\Interfaces\ItemWithCustomProperties;
use SoftRules\PHP\Interfaces\Renderable;
use SoftRules\PHP\Interfaces\RenderableWrapper;
use SoftRules\PHP\UI\Button;
use SoftRules\PHP\UI\CustomProperty;
use SoftRules\PHP\UI\Group;
use SoftRules\PHP\UI\UIClass;
use Stringable;

final class HtmlRenderer implements Stringable
{
    private string $html = '';
    private int $currentPage;
    public readonly int $totalPages;
    public readonly DOMDocument $userInterfaceData;
    /**
     * @var Collection<int, BaseItemInterface>
     */
    private readonly Collection $allItems;
//    private DOMDocument $SR_XML;

    public function __construct(UIClass $UIClass)
    {
        $this->totalPages = $UIClass->getPages();
        $this->currentPage = $UIClass->getPage();
        $this->userInterfaceData = $UIClass->getUserinterfaceData();
//        $this->SR_XML = $UIClass->getSoftRulesXml();
        $this->allItems = $UIClass->items;

        $this->html .= "<p>Pagina: {$this->currentPage} v/d {$this->totalPages} Pagina's</p>";

        $this->userInterfaceItems($UIClass->items);
    }

    /**
     * @param Collection<int, BaseItemInterface> $items
     */
    private function userInterfaceItems(Collection $items): void
    {
        foreach ($items as $item) {
            $itemName = class_basename($item);
            $this->html .= '<div>';
            $this->html .= $itemName;
            if ($item instanceof Button) {
                $this->html .= ' Text: <b>' . $item->getText() . '</b>';
            } elseif ($item instanceof Group) {
                $this->html .= ' Type: <b>' . $item->getType()->value . '</b> ' . $item->getName();
            }
            if ($item instanceof ItemWithCustomProperties) {
                $this->html .= $this->getCustomPropertyDescriptions($item);
            }

            $VisibleText = '';

            if (! $this->itemVisible($item)) {
                $VisibleText = ' Niet tonen (invisible)';
            }

            $this->html .= $VisibleText . '<br>';
            $this->html .= '</div>';

            if ($item instanceof RenderableWrapper) {
                $this->html .= $item->renderOpeningTags();
                $this->userInterfaceItems($item->getItems());
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
            }

            if ($item instanceof Renderable) {
                $this->html .= $item->render();
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

    public function itemVisible(BaseItemInterface $item): bool
    {
        return $item->getVisibleExpression()->value($this->allItems, $this->userInterfaceData);
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
