<?php declare(strict_types=1);

namespace SoftRules\PHP;

use DOMDocument;
use SoftRules\PHP\Contracts\Renderable;
use SoftRules\PHP\Contracts\RenderableWrapper;
use SoftRules\PHP\Contracts\UI\Components\ButtonComponentContract;
use SoftRules\PHP\Contracts\UI\Components\GroupComponentContract;
use SoftRules\PHP\Contracts\UI\ComponentWithCustomPropertiesContract;
use SoftRules\PHP\Contracts\UI\CustomPropertyContract;
use SoftRules\PHP\Contracts\UI\UiComponentContract;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;
use SoftRules\PHP\UI\SoftRulesFormData;
use Stringable;

final class HtmlRenderer implements Stringable
{
    private string $html = '';

    private int $currentPage;

    public readonly int $totalPages;

    public readonly DOMDocument $userInterfaceData;

    private readonly UiComponentsCollection $allComponents;

//    private DOMDocument $SR_XML;

    public function __construct(SoftRulesFormData $UIClass)
    {
        $this->totalPages = $UIClass->getPages();
        $this->currentPage = $UIClass->getPage();
        $this->userInterfaceData = $UIClass->getUserInterfaceData();
//        $this->SR_XML = $UIClass->getSoftRulesXml();
        $this->allComponents = $UIClass->components;

        $this->html .= $this->WaitScreen();
        $this->html .= "<p>Pagina: {$this->currentPage} v/d {$this->totalPages} Pagina's</p>";
        $this->html .= "<div class='errorContainer alert alert-danger' style='margin-top: 2px; display:none' data-type='Danger' id='messageAlert'></div>";

        $this->renderComponents($UIClass->components);
    }

    private function renderComponents(UiComponentsCollection $components): void
    {
        foreach ($components as $component) {
            $componentName = class_basename($component);

            if ($component instanceof RenderableWrapper) {
                $this->html .= $component->renderOpeningTags();
                $this->renderComponents($component->getComponents());
                $this->html .= $component->renderClosingTags();

                if ($component instanceof GroupComponentContract && $component->shouldHavePagination()) {
                    $this->html .= "<div class='row pagination-row' style='display: flex; gap: 5px;'>";

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
                $this->html .= $component->render();
            }
        }
    }

    private function getCustomPropertyDescriptions(ComponentWithCustomPropertiesContract $component): string
    {
        if ($component->getCustomProperties()->isEmpty()) {
            return '';
        }

        $description = $component->getCustomProperties()
            ->implode(fn (CustomPropertyContract $customProperty): string => "{$customProperty->getName()}={$customProperty->getValue()}", ', ');

        return " {$component->getCustomProperties()->count()} Custom Properties ({$description})";
    }

    public function itemVisible(UiComponentContract $component): bool
    {
        return $component->getVisibleExpression()->value($this->allComponents, $this->userInterfaceData);
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

    public function WaitScreen(): string{
        return @"<div id='waitScreen' class='waitScreen' style='display: none;'>
        <div class='loadingscreen_div'><img src='https://www.softrules.com/wp-content/themes/softrules/assets/logo.jpg' alt='My Tp / SoftRules©' style='text-align: center; width:250;height:166px;'>
        <div id='squaresWaveG'>
                     <div id='squaresWaveG_1' class='squaresWaveG'></div>
                     <div id='squaresWaveG_2' class='squaresWaveG'></div>
                     <div id='squaresWaveG_3' class='squaresWaveG'></div>
                     <div id='squaresWaveG_4' class='squaresWaveG'></div>
                     <div id='squaresWaveG_5' class='squaresWaveG'></div>
                     <div id='squaresWaveG_6' class='squaresWaveG'></div>
                     <div id='squaresWaveG_7' class='squaresWaveG'></div>
                     <div id='squaresWaveG_8' class='squaresWaveG'></div>
     </div></div></div>";

    }
}
