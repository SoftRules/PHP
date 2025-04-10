<?php declare(strict_types=1);

namespace SoftRules\PHP;

use DOMDocument;
use SoftRules\PHP\Contracts\Renderable;
use SoftRules\PHP\Contracts\RenderableWrapper;
use SoftRules\PHP\Contracts\UI\Components\GroupComponentContract;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;
use SoftRules\PHP\UI\SoftRulesFormData;
use Stringable;

final class HtmlRenderer implements Stringable
{
    private string $html = '';

    private readonly int $currentPage;

    public readonly int $totalPages;

    public readonly DOMDocument $userInterfaceData;

    public function __construct(SoftRulesFormData $UIClass)
    {
        $this->totalPages = $UIClass->getPages();
        $this->currentPage = $UIClass->getPage();
        $this->userInterfaceData = $UIClass->getUserInterfaceData();

        $this->renderComponents($UIClass->components, $this->userInterfaceData);
    }

    private function renderComponents(UiComponentsCollection $components, $userInterfaceData): void
    {
        foreach ($components as $component) {
            if ($component instanceof RenderableWrapper) {
                $this->html .= $component->renderOpeningTags($components, $userInterfaceData);
                $this->renderComponents($component->getComponents(), $userInterfaceData);
                $this->html .= $component->renderClosingTags();

                if ($component instanceof GroupComponentContract && $component->shouldHavePagination()) {
                    $this->html .= "<div class='pagination-row' style='display: flex; gap: 5px; margin-top: 15px;'>";

                    $nextPageID = $this->currentPage;
                    $previousPageID = $this->currentPage;

                    if ($this->hasPreviousPage() && $component->shouldHavePreviousPageButton()) {
                        $this->html .= $this->getPreviousButtonHtml($component->getGroupID(), $previousPageID);
                    }

                    if ($this->isLastPage()) {
                        if ($component->shouldHaveNextPageButton()) {
                            $this->html .= $this->getGoButtonHtml();
                        }
                    } elseif ($this->hasNextPage() && $component->shouldHaveNextPageButton()) {
                        $this->html .= $this->getNextButtonHtml($component->getGroupID(), $nextPageID);
                    }

                    $this->html .= '</div>';
                }
            }

            if ($component instanceof Renderable) {
                $this->html .= $component->render($components, $userInterfaceData);
            }
        }
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
