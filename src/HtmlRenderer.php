<?php declare(strict_types=1);

namespace SoftRules\PHP;

use DOMDocument;
use SoftRules\PHP\Interfaces\ComponentWithCustomProperties;
use SoftRules\PHP\Interfaces\Renderable;
use SoftRules\PHP\Interfaces\RenderableWrapper;
use SoftRules\PHP\Interfaces\UiComponentInterface;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;
use SoftRules\PHP\UI\Components\Button;
use SoftRules\PHP\UI\Components\Group;
use SoftRules\PHP\UI\CustomProperty;
use SoftRules\PHP\UI\SoftRulesForm;
use Stringable;

final class HtmlRenderer implements Stringable
{
    private string $html = '';
    private int $currentPage;
    public readonly int $totalPages;
    public readonly DOMDocument $userInterfaceData;
    private readonly UiComponentsCollection $allComponents;

//    private DOMDocument $SR_XML;

    public function __construct(SoftRulesForm $UIClass)
    {
        $this->totalPages = $UIClass->getPages();
        $this->currentPage = $UIClass->getPage();
        $this->userInterfaceData = $UIClass->getUserInterfaceData();
//        $this->SR_XML = $UIClass->getSoftRulesXml();
        $this->allComponents = $UIClass->components;

        $this->html .= "<p>Pagina: {$this->currentPage} v/d {$this->totalPages} Pagina's</p>";

        $this->renderComponents($UIClass->components);
    }

    private function renderComponents(UiComponentsCollection $components): void
    {
        foreach ($components as $component) {
            $componentName = class_basename($component);
            $this->html .= '<div>';
            $this->html .= $componentName;

            if ($component instanceof Button) {
                $this->html .= ' Text: <b>' . $component->getText() . '</b>';
            } elseif ($component instanceof Group) {
                $this->html .= ' Type: <b>' . $component->getType()->value . '</b> ' . $component->getName();
            }

            if ($component instanceof ComponentWithCustomProperties) {
                $this->html .= $this->getCustomPropertyDescriptions($component);
            }

            $VisibleText = '';

            if (! $this->itemVisible($component)) {
                $VisibleText = ' Niet tonen (invisible)';
            }

            $this->html .= $VisibleText . '<br>';
            $this->html .= '</div>';

            if ($component instanceof RenderableWrapper) {
                $this->html .= $component->renderOpeningTags();
                $this->renderComponents($component->getComponents());
                $this->html .= $component->renderClosingTags();

                if ($component instanceof Group && $component->shouldHavePagination()) {
                    $this->html .= "<div class='row pagination-row'>";

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

    private function getCustomPropertyDescriptions(ComponentWithCustomProperties $component): string
    {
        if ($component->getCustomProperties()->isEmpty()) {
            return '';
        }

        $description = $component->getCustomProperties()
            ->implode(fn (CustomProperty $customProperty) => "{$customProperty->getName()}={$customProperty->getValue()}", ', ');

        return " {$component->getCustomProperties()->count()} Custom Properties ({$description})";
    }

    public function itemVisible(UiComponentInterface $component): bool
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
}
