<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Components;

use DOMElement;
use SoftRules\PHP\Contracts\Renderable;
use SoftRules\PHP\Contracts\RenderableWrapper;
use SoftRules\PHP\Contracts\UI\Components\GroupComponentContract;
use SoftRules\PHP\Contracts\UI\ExpressionContract;
use SoftRules\PHP\Contracts\UI\ParameterContract;
use SoftRules\PHP\Contracts\UI\UiComponentContract;
use SoftRules\PHP\Enums\eGroupType;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;
use SoftRules\PHP\UI\CustomProperty;
use SoftRules\PHP\UI\Expression;
use SoftRules\PHP\UI\Parameter;
use SoftRules\PHP\UI\Style\GroupComponentStyle;
use SoftRules\PHP\UI\Style\StyleData;

class Group implements GroupComponentContract, RenderableWrapper
{
    use HasCustomProperties;
    use ParsedFromXml;

    public static ?GroupComponentStyle $style = null;

    private string $groupID;

    private string $name;

    private eGroupType $type;

    private $updateUserInterface;

    private string $pageID;

    private $suppressItemsWhenInvisible;

    private UiComponentsCollection $components;

    private array $headerItems = [];

    private string $description = '';

    private ExpressionContract $visibleExpression;

    private ParameterContract $parameter;

    private ?eGroupType $parentGroupType = eGroupType::none;

    public function __construct()
    {
        $this->components = new UiComponentsCollection();
        $this->setVisibleExpression(new Expression());
    }

    public static function setStyle(GroupComponentStyle $style): void
    {
        self::$style = $style;
    }

    public function getStyle(): GroupComponentStyle
    {
        return self::$style ?? GroupComponentStyle::bootstrapThree();
    }

    public function setGroupID(string $groupID): void
    {
        $this->groupID = str_replace('|', '_', $groupID);
    }

    public function getGroupID(): string
    {
        return $this->groupID;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setType(eGroupType|string $type): void
    {
        if ($type instanceof eGroupType) {
            $this->type = $type;

            return;
        }

        $this->type = eGroupType::from(strtolower($type));
    }

    public function getType(): eGroupType
    {
        return $this->type;
    }

    public function setUpdateUserInterface($updateUserInterface): void
    {
        $this->updateUserInterface = $updateUserInterface;
    }

    public function getUpdateUserInterface()
    {
        return $this->updateUserInterface;
    }

    public function setPageID(string $pageID): void
    {
        $this->pageID = $pageID;
    }

    public function getPageID(): string
    {
        return $this->pageID;
    }

    public function setSuppressItemsWhenInvisible($suppressItemsWhenInvisible): void
    {
        $this->suppressItemsWhenInvisible = $suppressItemsWhenInvisible;
    }

    public function getSuppressItemsWhenInvisible()
    {
        return $this->suppressItemsWhenInvisible;
    }

    public function addComponent(UiComponentContract $component): void
    {
        $component->setParentGroupType($this->getType());
        $this->components->add($component);
    }

    public function getComponents(): UiComponentsCollection
    {
        return $this->components;
    }

    public function getHeaderItems(): array
    {
        return $this->headerItems;
    }

    public function addHeaderItem($headerItem): void
    {
        $headerItem->setParentGroupType(eGroupType::tableheader);
        $this->headerItems[] = $headerItem;
    }

    public function setVisibleExpression(ExpressionContract $visibleExpression): void
    {
        $this->visibleExpression = $visibleExpression;
    }

    public function getVisibleExpression(): ExpressionContract
    {
        return $this->visibleExpression;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setParameter(ParameterContract $parameter): void
    {
        $this->parameter = $parameter;
    }

    public function getParameter(): ParameterContract
    {
        return $this->parameter;
    }

    public function setParentGroupType(eGroupType $parentGroupType): void
    {
        $this->parentGroupType = $parentGroupType;
    }

    public function getParentGroupType(): ?eGroupType
    {
        return $this->parentGroupType;
    }

    public function parse(DOMElement $DOMElement): static
    {
        /** @var DOMElement $childNode */
        foreach ($DOMElement->childNodes as $childNode) {
            switch ($childNode->nodeName) {
                case 'GroupID':
                    $this->setGroupID($childNode->nodeValue);
                    break;
                case 'Name':
                    $this->setName($childNode->nodeValue);
                    break;
                case 'Type':
                    $this->setType($childNode->nodeValue);
                    break;
                case 'UpdateUserInterface':
                    $this->setUpdateUserInterface($childNode->nodeValue);
                    break;
                case 'PageID':
                    $this->setPageID($childNode->nodeValue);
                    break;
                case 'DisplayType':
                case 'RepeatingPath':
                    //no need to parse
                    break;
                case 'SuppressItemsWhenInvisible':
                    $this->setSuppressItemsWhenInvisible($childNode->nodeValue);
                    break;
                case 'CustomProperties':
                    /** @var DOMElement $grandChildNode */
                    foreach ($childNode->childNodes as $grandChildNode) {
                        $this->addCustomProperty(CustomProperty::createFromDomNode($grandChildNode));
                    }

                    break;
                case 'VisibleExpression':
                    $this->setVisibleExpression(Expression::createFromDomNode($childNode));
                    break;
                case 'Parameter':
                    $this->setParameter(Parameter::createFromDomNode($childNode));
                    break;
                case 'ParameterList':
                case 'HeaderItems':
                    /** @var DOMElement $grandChildNode */
                    foreach ($childNode->childNodes as $grandChildNode) {
                        switch ($grandChildNode->nodeName) {
                            case 'Label':
                                $this->addHeaderItem(Label::createFromDomNode($grandChildNode));
                                break;
                            case 'Question':
                                $this->addHeaderItem(Question::createFromDomNode($grandChildNode));
                                break;
                            default:
                                break;
                        }
                    }

                    break;
                case 'Items':
                    /** @var DOMElement $grandChildNode */
                    foreach ($childNode->childNodes as $grandChildNode) {
                        switch ($grandChildNode->nodeName) {
                            case 'Group':
                                $this->addComponent(self::createFromDomNode($grandChildNode));
                                break;
                            case 'Question':
                                $this->addComponent(Question::createFromDomNode($grandChildNode));
                                break;
                            case 'Label':
                                $this->addComponent(Label::createFromDomNode($grandChildNode));
                                break;
                            case 'Button':
                                $this->addComponent(Button::createFromDomNode($grandChildNode));
                                break;
                            default:
                                break;
                        }
                    }

                    break;
                default:
                    echo 'Group Not implemented yet:' . $childNode->nodeName . '<br>';
                    break;
            }
        }

        return $this;
    }

    public function shouldHavePagination(): bool
    {
        return $this->getType() === eGroupType::page;
    }

    public function shouldHaveNextPageButton(): bool
    {
        return $this->getCustomPropertyByName('nextbuttonvisible')?->isTrue() ?? true;
    }

    public function shouldHavePreviousPageButton(): bool
    {
        return $this->getCustomPropertyByName('previousbuttonvisible')?->isTrue() ?? true;
    }

    public function renderOpeningTags(): string
    {
        $style = match ($this->getType()) {
            eGroupType::page => $this->getStyle()->page,
            // TODO: add other styles and map them here
            default => null,
        };

        return match ($this->getType()) {
            eGroupType::page => $this->getPageOpeningsTag($style),
            eGroupType::box => $this->getBoxOpeningsTag($style),
            eGroupType::grid => $this->getGridOpeningsTag($style),
            eGroupType::gridrow => $this->getGridRowOpeningsTag($style),
            eGroupType::gridcolumn => $this->getGridColumnOpeningsTag($style),
            eGroupType::table => $this->getTableOpeningsTag($style),
            eGroupType::row => $this->getTableRowOpeningsTag($style),
            eGroupType::expandable => $this->getExpandableOpeningsTag($style),
            default => 'Group Type not implemented ' . $this->getType()->value . '<br>',
        };
    }

    public function getPageOpeningsTag(?StyleData $style): string
    {
        $html = "<div class='card sr-group sr-group-{$this->getType()->value} {$style?->class}' style='{$style?->inlineStyle}' {$this->styleTypeProperty()}>";

        if ($this->getName() !== '') {
            $html .= "<div class='card-header'>";
            $html .= "<div class='col-sm-11 header-col'>";
            $html .= "<span>{$this->getName()}</span>";
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= "<div class='show'>";

        return $html . "<div class='card-body'>";
    }

    public function getBoxOpeningsTag(?StyleData $style): string
    {
        $html = "<div class='card sr-group sr-group-{$this->getType()->value} {$style?->class}' style='{$style?->inlineStyle}' {$this->styleTypeProperty()} data-id='{$this->getGroupID()}'>";

        if ($this->getName() !== '') {
            $html .= "<div class='card-header'>";
            $html .= "<div class='col-sm-11 header-col'>";
            $html .= "<span>{$this->getName()}</span>";
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= "<div class='show'>";

        return $html . "<div class='card-body'>";
    }

    public function getGridOpeningsTag(?StyleData $style): string
    {
        return "<div class='minimal grid sr-group sr-group-{$this->getType()->value} {$style?->class}' style='{$style?->inlineStyle}' {$this->styleTypeProperty()}>";
    }

    public function getGridRowOpeningsTag(?StyleData $style): string
    {
        return "<div class='row sr-group sr-group-{$this->getType()->value} {$style?->class}' style='{$style?->inlineStyle}' {$this->styleTypeProperty()} data-id='{$this->getGroupID()}'>";
    }

    public function getGridColumnOpeningsTag(?StyleData $style): string
    {
        $columnwidth = $this->getCustomPropertyByName('columnwidth')?->getValue() ?? '12';

        return "<div class='col-sm-{$columnwidth} sr-group sr-group-{$this->getType()->value} {$style?->class}' style='{$style?->inlineStyle}' {$this->styleTypeProperty()} data-columnwidth={$columnwidth} data-id='{$this->getGroupID()}'>";
    }

    public function getTableOpeningsTag(?StyleData $style): string
    {
        $html = "<div class='table-container sr-group-{$this->getType()->value} {$style?->class}' style='{$style?->inlineStyle}' {$this->styleTypeProperty()} data-id='{$this->getGroupID()}'>";
        $html .= "<table class='table sr-table'>";

        if ($this->getHeaderItems()) {
            $html .= "<thead class='sr-table-thead'><tr>";

            foreach ($this->getHeaderItems() as $item) {
                if ($item instanceof Renderable) {
                    $html .= $item->render();
                }
            }

            $html .= '</tr></thead>';
        }

        return $html . "<tbody class='sr-table-tbody'>";
    }

    public function getTableRowOpeningsTag(?StyleData $style): string
    {
        return "<tr class='sr-table-tr group sr-group-{$this->getType()->value} {$style?->class}' style='{$style?->inlineStyle}' {$this->styleTypeProperty()} data-id='{$this->getGroupID()}'>";
    }

    public function getExpandableOpeningsTag(?StyleData $style): string
    {
        $html = "<div class='container sr-group-{$this->getType()->value} {$style?->class}' style='{$style?->inlineStyle}' {$this->styleTypeProperty()} data-id='{$this->getGroupID()}'>";
        $html .= "<div class='card-header collapsed' data-toggle='collapse' data-target='#sr_{$this->getGroupID()}Body'>";
        $html .= "<div class='col-sm-11 header-col'>";
        $html .= "<span class='fas'/>";
        $html .= "<span id='sr_{$this->getGroupID()}Header'>{$this->getName()}</span>";
        $html .= '</div>'; // header-col
        $html .= '</div>'; // card-header
        $html .= "<div id='sr_{$this->getGroupID()}Body' class='collapse'>";

        return $html . "<div class='card-body'>";
    }

    public function renderClosingTags(): string
    {
        return match ($this->getType()) {
            eGroupType::page => '</div></div></div>',
            eGroupType::box => '</div></div></div>',
            eGroupType::grid => '</div>',
            eGroupType::gridrow => '</div>',
            eGroupType::gridcolumn => '</div>',
            eGroupType::table => '</tbody></table></div>',
            eGroupType::row => '</tr>',
            eGroupType::expandable => '</div></div></div>',
            default => '</div>',
        };
    }
}
