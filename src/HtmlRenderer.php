<?php declare(strict_types=1);

namespace SoftRules\PHP;

use DOMDocument;
use SoftRules\PHP\Enums\eButtonType;
use SoftRules\PHP\Enums\eGroupType;
use SoftRules\PHP\Interfaces\ISoftRules_Base;
use SoftRules\PHP\UI\Button;
use SoftRules\PHP\UI\Group;
use SoftRules\PHP\UI\Label;
use SoftRules\PHP\UI\Question;
use SoftRules\PHP\UI\UIClass;
use Stringable;

final class HtmlRenderer implements Stringable
{
    private string $html = '';
    private int $currentPage;
    private readonly int $totalPages;
    private readonly DOMDocument $userinterfaceData;
    private readonly array $allItems;
    private DOMDocument $SR_XML;

    public function __construct(UIClass $UIClass)
    {
        $this->totalPages = $UIClass->getPages();
        $this->currentPage = $UIClass->getPage();
        $this->userinterfaceData = $UIClass->getUserinterfaceData();
        $this->SR_XML = $UIClass->getSoftRulesXml();
        $this->allItems = $UIClass->getItems();

        $this->html .= "<p>Pagina: {$this->currentPage} v/d {$this->totalPages} Pagina's</p>";

        $this->userinterfaceItems($UIClass->getItems(), false);
    }

    /**
     * @param ISoftRules_Base[] $Items
     */
    private function userinterfaceItems(array $Items, bool $isUpdate): void
    {
        foreach ($Items as $item) {
            if ($item instanceof Group) {
                $id = $item->getGroupID();
                $VisibleText = "";
                $nextButtonVisible = true;
                $previousButtonVisible = true;

                $custompropertyDescription = $this->getCustomPropertyDescriptions($item);

                foreach ($item->getCustomProperties() as $customproperty) {
                    switch (strtolower($customproperty->getName())) {
                        case "nextbuttonvisible":
                            $nextButtonVisible = $customproperty->getValue();
                            break;
                        case "previousbuttonvisible":
                            $previousButtonVisible = $customproperty->getValue();
                            break;
                        case "styletype":
                            $StyleType = " data-styleType='" . $customproperty->getValue() . "'";
                            break;
                        case "pagenum":
                            $PageNum = " data-pagenum='" . $customproperty->getValue() . "'";
                            break;
                        case "numpages":
                            $NumPages = " data-numpages='" . $customproperty->getValue() . "'";
                            break;
                        case "hidebutton":
                            $HideButton = " data-hidebutton='" . $customproperty->getValue() . "'";
                            break;
                        case "noborder":
                            break;
                        case "headertext":
                            break;
                        case "defaultexpandablebehaviour":
                            break;
                        case "rowselect":
                            break;
                        case "tabelsorteeropties":
                            break;
                        default:
                            echo "Group customproperty not implemented " . $customproperty->getName() . "<br>";
                            break;
                    }
                }

                if (! $this->itemVisible($item)) {
                    $VisibleText = " Niet tonen (invisible)";
                }

                $this->html .= "Group Type: <b>" . $item->getType()->value . "</b> " . $item->getName() . $custompropertyDescription . $VisibleText . "<br>";

                switch ($item->getType()) {
                    case eGroupType::page:
                        $nextPageID = $this->currentPage;
                        $previousPageID = $this->currentPage;

                        $this->html .= "<div class='col-xs-12'>";
                        $this->html .= "<ul>";
                        $this->userinterfaceItems($item->getItems(), false);
                        $this->html .= "</ul>";

                        $this->html .= "<div class='row pagination-row'>";

                        if ($this->currentPage === $this->totalPages) {
                            if ($this->totalPages !== 1 && $previousButtonVisible === "True") {
                                $this->getPreviousButtonHtml($id, $previousPageID);
                            }

                            if ($nextButtonVisible === "True") {
                                $this->getGoButtonHtml();
                            }
                        } elseif ($this->currentPage <= 1) {
                            if ($nextButtonVisible === "True") {
                                $this->getNextButtonHtml($id, $nextPageID);
                            }
                        } elseif ($this->currentPage >= 2 && $this->currentPage <= $this->totalPages - 1) {
                            if ($previousButtonVisible === "True") {
                                $this->getPreviousButtonHtml($id, $previousPageID);
                            }

                            if ($nextButtonVisible === "True") {
                                $this->getNextButtonHtml($id, $nextPageID);
                            }
                        }

                        $this->html .= "</div>";
                        $this->html .= "</div>";
                        break;
                    case eGroupType::box:
                        $this->html .= "<ul>";
                        $this->userinterfaceItems($item->getItems(), false);
                        $this->html .= "</ul>";
                        break;
                    case eGroupType::expandable:
                        $this->html .= "<ul>";
                        $this->userinterfaceItems($item->getItems(), false);
                        $this->html .= "</ul>";
                        break;
                    case eGroupType::table:
                        $this->html .= "<ul>";
                        $this->userinterfaceItems($item->getItems(), false);
                        $this->html .= "</ul>";
                        break;
                    case eGroupType::grid:
                        $this->html .= "<ul>";
                        $this->userinterfaceItems($item->getItems(), false);
                        $this->html .= "</ul>";
                        break;
                    case eGroupType::gridrow:
                        $this->html .= "<ul>";
                        $this->userinterfaceItems($item->getItems(), false);
                        $this->html .= "</ul>";
                        break;
                    case eGroupType::gridcolumn:
                        $this->html .= "<ul>";
                        $this->userinterfaceItems($item->getItems(), false);
                        $this->html .= "</ul>";
                        break;
                    case eGroupType::row:
                        $this->html .= "<ul>";
                        $this->userinterfaceItems($item->getItems(), false);
                        $this->html .= "</ul>";
                        break;
                    default:
                        echo "Group Type not implemented " . $item->getType() . "<br>";
                        break;
                }
            } elseif ($item instanceof Question) {
                $id = $item->getQuestionID();
                $VisibleText = "";
                $textvalues = $this->getTextValuesDescription($item);

                if (! $this->itemVisible($item)) {
                    $VisibleText = " Niet tonen (invisible)";
                }

                $this->html .= "<li>";
                $this->html .= "Question: {$item->getDescription()}({$item->getName()}) Value: {$item->getValue()} UpdateUserinterface: {$item->getUpdateUserinterface()}{$textvalues}{$VisibleText}";
                $this->html .= "</li>";
            } elseif ($item instanceof Label) {
                $VisibleText = "";

                if (! $this->itemVisible($item)) {
                    $VisibleText = " Niet tonen (invisible)";
                }

                $this->html .= "Label Text: {$item->getText()}{$VisibleText}<br/>";
            } elseif ($item instanceof Button) {
                $id = $item->getButtonID();
                $StyleType = "";
                $nextPageID = $this->currentPage;
                $VisibleText = "";
                $custompropertyDescription = $this->getCustomPropertyDescriptions($item);

                if (strtolower($item->getDisplayType()) === "tile") {
                    $tile = true;
                }

                if (! $this->itemVisible($item)) {
                    $VisibleText = " Niet tonen (invisible)";
                }

                foreach ($item->getCustomProperties() as $customproperty) {
                    switch (strtolower($customproperty->getName())) {
                        case "nextpage":
                            $name = $customproperty->getValue();
                            break;
                        case "pictureurl":
                            $PictureUrl = $customproperty->getValue();
                            break;
                        case "width":
                            $width = $customproperty->getValue();
                            break;
                        case "height":
                            $height = $customproperty->getValue();
                            break;
                        case "styletype":
                            $StyleType = " data-styleType='" . $customproperty->getValue() . "'";
                            break;
                        case "align":
                            $Align = " data-align='" . $customproperty->getValue() . "'";
                            break;
                        default:
                            break;
                    }
                }

                $buttonfunction = match ($item->getType()) {
                    eButtonType::submit => "processButton",
                    eButtonType::navigate, eButtonType::update => "updateButton",
                    default => '',
                };
                $this->html .= "<li>";
                $this->html .= "Button Text: " . $item->getText() . $custompropertyDescription . $VisibleText . "<br>";
                $this->html .= "</li>";
                $this->html .= "<ul><li>";
                $this->html .= "<button type=\"button\" class=\"{$buttonfunction} btn btn-default\" data-type='button' data-id=" . $id . ">{$item->getText()}</button>";
                $this->html .= "</li></ul>";
            }
        }
    }

    private function getNextButtonHtml(string $id, int $nextPageID): void
    {
        $this->html .= "<button type='button' class='nextButton btn btn-primary sr-nextpage' data-type='Group' data-nextid='" . $nextPageID . "' data-id=" . $id . ">Volgende</button>";
    }

    private function getPreviousButtonHtml(string $id, int $previousPageID): void
    {
        $this->html .= "<button type='button' class='previousButton btn btn-primary sr-previouspage' data-type='Group' data-previousid='" . $previousPageID . "' data-id=" . $id . ">Vorige</button>";
    }

    private function getGoButtonHtml(): void
    {
        $this->html .= "<button type='button' class='nextButton btn btn-primary sr-nextpage' data-type='Process' id='ProcessButton' onclick='ProcessValidation()'>Volgende</button>";
    }

    private function getCustomPropertyDescriptions(Group|Button $item): string
    {
        $amountCustomProperties = count($item->getCustomProperties());

        if ($amountCustomProperties > 0) {
            $description = "";

            foreach ($item->getCustomProperties() as $customproperty) {
                if ($description !== "") {
                    $description .= ", ";
                }
                $description .= "{$customproperty->getName()}={$customproperty->getValue()}";
            }

            return " {$amountCustomProperties} Custom Properties ({$description})";
        }

        return '';
    }

    private function getTextValuesDescription(Question $item): string
    {
        $amountTextValues = count($item->getTextValues());

        if ($amountTextValues > 0) {
            $items = "";

            foreach ($item->getTextValues() as $textvalueItem) {
                if ($items !== "") {
                    $items .= ", ";
                }

                $items .= "{$textvalueItem->getValue()}-{$textvalueItem->getText()}";
            }

            return " Aantal TextValues: {$amountTextValues} ({$items})";
        }

        return '';
    }

    private function itemVisible(ISoftRules_Base $item): bool
    {
        return (bool) $item->getVisibleExpression()->Value($this->allItems, $this->userinterfaceData);
    }

    public function __toString(): string
    {
        return $this->html;
    }
}
