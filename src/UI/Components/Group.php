<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Components;

use DOMElement;
use SoftRules\PHP\Contracts\RenderableWrapper;
use SoftRules\PHP\Contracts\UI\Components\GroupComponentContractProperties;
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

class Group implements GroupComponentContractProperties, RenderableWrapper
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

    public function parse(DOMElement $DOMElement): static
    {
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
                case 'SuppressItemsWhenInvisible':
                    $this->setSuppressItemsWhenInvisible($childNode->nodeValue);
                    break;
                case 'CustomProperties':
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
                case 'RepeatingPath':
                    //no need to parse
                    break;
                case 'HeaderItems':
                    foreach ($childNode->childNodes as $grandChildNode) {
                        switch ($grandChildNode->nodeName) {
                            case 'Group':
                                $this->addHeaderItem(self::createFromDomNode($grandChildNode));
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
//        foreach ($this->getCustomProperties() as $customproperty) {
//            switch (strtolower($customproperty->getName())) {
//                case "styletype":
//                    $StyleType = " data-styleType='" . $customproperty->getValue() . "'";
//                    break;
//                case "pagenum":
//                    $PageNum = " data-pagenum='" . $customproperty->getValue() . "'";
//                    break;
//                case "numpages":
//                    $NumPages = " data-numpages='" . $customproperty->getValue() . "'";
//                    break;
//                case "hidebutton":
//                    $HideButton = " data-hidebutton='" . $customproperty->getValue() . "'";
//                    break;
//                case "noborder":
//                    break;
//                case "headertext":
//                    break;
//                case "defaultexpandablebehaviour":
//                    break;
//                case "rowselect":
//                    break;
//                case "tabelsorteeropties":
//                    break;
//                default:
//                    echo "Group customproperty not implemented " . $customproperty->getName() . "<br>";
//                    break;
//            }
//        }

        $styleType = $this->getCustomPropertyByName('styletype')?->getValue() ?? 'default';

        $style = match ($this->getType()) {
            eGroupType::page => $this->getStyle()->page,
            default => null,
        };

        $html = "<div class='sr-group sr-group-{$this->getType()->value} {$style?->class}' style='{$style?->inlineStyle}' data-styleType='{$styleType}'>";

        return $html . match ($this->getType()) {
            eGroupType::page => '<ul>',
            eGroupType::box, eGroupType::expandable, eGroupType::table, eGroupType::grid, eGroupType::gridrow, eGroupType::gridcolumn, eGroupType::row => '<ul>',
            default => 'Group Type not implemented ' . $this->getType()->value . '<br>',
        };
    }

    public function renderClosingTags(): string
    {
        $html = match ($this->getType()) {
            eGroupType::page => '</ul>',
            eGroupType::box, eGroupType::expandable, eGroupType::table, eGroupType::grid, eGroupType::gridrow, eGroupType::gridcolumn, eGroupType::row => '</ul>',
            default => '',
        };

        return $html . '</div>';
    }
}
