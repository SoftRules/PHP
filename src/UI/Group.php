<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use Illuminate\Support\Collection;
use SoftRules\PHP\Enums\eGroupType;
use SoftRules\PHP\Interfaces\IExpression;
use SoftRules\PHP\Interfaces\IGroup;
use SoftRules\PHP\Interfaces\IParameter;
use SoftRules\PHP\Interfaces\ISoftRules_Base;
use SoftRules\PHP\Interfaces\RenderableWrapper;
use SoftRules\PHP\Traits\HasCustomProperties;
use SoftRules\PHP\Traits\ParsedFromXml;

class Group implements IGroup, RenderableWrapper
{
    use HasCustomProperties;
    use ParsedFromXml;

    private $groupID;

    private $name;

    private eGroupType $type;

    private $updateUserinterface;

    private $pageID;

    private $suppressItemsWhenInvisible;

    /**
     * @var Collection<int, ISoftRules_Base>
     */
    private Collection $items;

    private array $headerItems = [];

    private $description;

    private IExpression $visibleExpression;

    private IParameter $parameter;

    public function __construct()
    {
        $this->items = new Collection();
        $this->setVisibleExpression(new Expression());
    }

    public function setGroupID($groupID): void
    {
        $this->groupID = $groupID;
    }

    public function getGroupID()
    {
        return str_replace('|', '_', (string) $this->groupID);
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName()
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

    public function setUpdateUserinterface($updateUserinterface): void
    {
        $this->updateUserinterface = $updateUserinterface;
    }

    public function getUpdateUserinterface()
    {
        return $this->updateUserinterface;
    }

    public function setPageID($pageID): void
    {
        $this->pageID = $pageID;
    }

    public function getPageID()
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

    public function addItem(ISoftRules_Base $item): void
    {
        $this->items->add($item);
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function setHeaderItems(array $headerItems): void
    {
        $this->headerItems = $headerItems;
    }

    public function getHeaderItems(): array
    {
        return $this->headerItems;
    }

    public function addHeaderItem($item): void
    {
        $this->headerItems[] = $item;
    }

    public function setVisibleExpression(IExpression $visibleExpression): void
    {
        $this->visibleExpression = $visibleExpression;
    }

    public function getVisibleExpression(): IExpression
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

    public function setParameter(Parameter $parameter): void
    {
        $this->parameter = $parameter;
    }

    public function getParameter(): Parameter
    {
        return $this->parameter;
    }

    public function parse(DOMNode $node): self
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case 'GroupID':
                    $this->setGroupID($item->nodeValue);
                    break;
                case 'Name':
                    $this->setName($item->nodeValue);
                    break;
                case 'Type':
                    $this->setType($item->nodeValue);
                    break;
                case 'UpdateUserInterface':
                    $this->setUpdateUserinterface($item->nodeValue);
                    break;
                case 'PageID':
                    $this->setPageID($item->nodeValue);
                    break;
                case 'SuppressItemsWhenInvisible':
                    $this->setSuppressItemsWhenInvisible($item->nodeValue);
                    break;
                case 'CustomProperties':
                    foreach ($item->childNodes as $cp) {
                        $this->addCustomProperty(CustomProperty::createFromDomNode($cp));
                    }
                    break;
                case 'VisibleExpression':
                    $this->setVisibleExpression(Expression::createFromDomNode($item));
                    break;
                case 'Parameter':
                    $this->setParameter(Parameter::createFromDomNode($item));
                    break;
                case 'ParameterList':
                case 'RepeatingPath':
                    //no need to parse
                    break;
                case 'HeaderItems':
                    foreach ($item->childNodes as $childNode) {
                        switch ($childNode->nodeName) {
                            case 'Group':
                                $this->addHeaderItem(self::createFromDomNode($childNode));
                                break;
                            case 'Question':
                                $this->addHeaderItem(Question::createFromDomNode($childNode));
                                break;
                            default:
                                break;
                        }
                    }
                    break;
                case 'Items':
                    foreach ($item->childNodes as $childNode) {
                        switch ($childNode->nodeName) {
                            case 'Group':
                                $this->addItem(self::createFromDomNode($childNode));
                                break;
                            case 'Question':
                                $this->addItem(Question::createFromDomNode($childNode));
                                break;
                            case 'Label':
                                $this->addItem(Label::createFromDomNode($childNode));
                                break;
                            case 'Button':
                                $this->addItem(Button::createFromDomNode($childNode));
                                break;
                            default:
                                break;
                        }
                    }
                    break;
                default:
                    echo 'Group Not implemented yet:' . $item->nodeName . '<br>';
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
        $html = '';

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

        switch ($this->getType()) {
            case eGroupType::page:
                $html .= "<div class='col-xs-12'>";
                $html .= '<ul>';
                break;
            case eGroupType::box:
            case eGroupType::expandable:
            case eGroupType::table:
            case eGroupType::grid:
            case eGroupType::gridrow:
            case eGroupType::gridcolumn:
            case eGroupType::row:
                $html .= '<ul>';
                break;
            default:
                echo 'Group Type not implemented ' . $this->getType()->value . '<br>';
                break;
        }

        return $html;
    }

    public function renderClosingTags(): string
    {
        $html = '';

        switch ($this->getType()) {
            case eGroupType::page:
                $html .= '</ul>';
                $html .= '</div>';
                break;
            case eGroupType::box:
            case eGroupType::expandable:
            case eGroupType::table:
            case eGroupType::grid:
            case eGroupType::gridrow:
            case eGroupType::gridcolumn:
            case eGroupType::row:
                $html .= '</ul>';
                break;
            default:
                echo 'Group Type not implemented ' . $this->getType()->value . '<br>';
                break;
        }

        return $html;
    }
}
