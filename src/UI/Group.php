<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMNode;
use SoftRules\PHP\Enums\eGroupType;
use SoftRules\PHP\Interfaces\IExpression;
use SoftRules\PHP\Interfaces\IGroup;

class Group implements IGroup
{
    private $GroupID;
    private $Name;
    private eGroupType $Type;
    private $UpdateUserinterface;
    private array $CustomProperties = [];
    private $PageID;
    private $SuppressItemsWhenInvisible;
    private array $Items = [];
    private array $HeaderItems = [];
    private $Description;
    private $VisibleExpression;
    private $Parameter;

    function __construct()
    {
        $this->setVisibleExpression(new Expression());
    }

    public function setGroupID($GroupID): void
    {
        $this->GroupID = $GroupID;
    }

    public function getGroupID()
    {
        return str_replace('|', '_', $this->GroupID);
    }

    public function setName($Name): void
    {
        $this->Name = $Name;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setType(eGroupType|string $Type): void
    {
        if ($Type instanceof eGroupType) {
            $this->Type = $Type;
            return;
        }

        $this->Type = eGroupType::from(strtolower($Type));
    }

    public function getType(): eGroupType
    {
        return $this->Type;
    }

    public function setUpdateUserinterface($UpdateUserinterface): void
    {
        $this->UpdateUserinterface = $UpdateUserinterface;
    }

    public function getUpdateUserinterface()
    {
        return $this->UpdateUserinterface;
    }

    public function setPageID($pageID): void
    {
        $this->PageID = $pageID;
    }

    public function getPageID()
    {
        return $this->PageID;
    }

    public function setSuppressItemsWhenInvisible($SuppressItemsWhenInvisible): void
    {
        $this->SuppressItemsWhenInvisible = $SuppressItemsWhenInvisible;
    }

    public function getSuppressItemsWhenInvisible()
    {
        return $this->SuppressItemsWhenInvisible;
    }

    public function setItems(array $Items): void
    {
        $this->funcionItems = $Items;
    }

    public function getItems(): array
    {
        return $this->Items;
    }

    public function setHeaderItems(array $HeaderItems): void
    {
        $this->HeaderItems = $HeaderItems;
    }

    public function getHeaderItems(): array
    {
        return $this->HeaderItems;
    }

    public function AddItem($Item): void
    {
        $this->Items[] = $Item;
    }

    public function AddHeaderItem($Item): void
    {
        $this->HeaderItems[] = $Item;
    }

    public function setVisibleExpression(IExpression $VisibleExpression): void
    {
        $this->VisibleExpression = $VisibleExpression;
    }

    public function getVisibleExpression(): IExpression
    {
        return $this->VisibleExpression;
    }

    public function setDescription(string $Description): void
    {
        $this->Description = $Description;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setCustomProperties(array $CustomProperties): void
    {
        $this->CustomProperties = $CustomProperties;
    }

    public function getCustomProperties(): array
    {
        return $this->CustomProperties;
    }

    public function setParameter(Parameter $Parameter): void
    {
        $this->Parameter = $Parameter;
    }

    public function getParameter(): Parameter
    {
        return $this->Parameter;
    }

    public function parse(DOMNode $node): void
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case "GroupID":
                {
                    $this->setGroupID($item->nodeValue);
                    break;
                }
                case "Name":
                {
                    $this->setName($item->nodeValue);
                    break;
                }
                case "Type":
                {
                    $this->setType($item->nodeValue);
                    break;
                }
                case "UpdateUserInterface":
                {
                    $this->setUpdateUserinterface($item->nodeValue);
                    break;
                }
                case 'PageID':
                {
                    $this->setPageID($item->nodeValue);
                    break;
                }
                case 'SuppressItemsWhenInvisible':
                {
                    $this->setSuppressItemsWhenInvisible($item->nodeValue);
                    break;
                }
                case 'CustomProperties':
                {
                    foreach ($item->childNodes as $cp) {
                        $customProperty = new CustomProperty();
                        $customProperty->parse($cp);
                        $this->CustomProperties[] = $customProperty;
                    }
                    break;
                }
                case 'VisibleExpression':
                {
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setVisibleExpression($expression);
                    break;
                }
                case 'Parameter':
                {
                    $parameter = new Parameter();
                    $parameter->parse($item);
                    $this->setParameter($parameter);
                    break;
                }
                case 'ParameterList':
                {
                    //no need to parse
                    break;
                }
                case 'RepeatingPath':
                {
                    //no need to parse
                    break;
                }
                case 'HeaderItems':
                {
                    foreach ($item->childNodes as $childNode) {
                        switch ($childNode->nodeName) {
                            case 'Group':
                            {
                                $newGroup = new Group();
                                $newGroup->parse($childNode);
                                $this->AddHeaderItem($newGroup);
                                break;
                            }
                            case 'Question':
                            {
                                $newQuestion = new Question();
                                $newQuestion->parse($childNode);
                                $this->AddHeaderItem($newQuestion);
                                break;
                            }
                            default:
                            {
                                break;
                            }
                        }
                    }
                    break;
                }
                case 'Items':
                {
                    foreach ($item->childNodes as $childNode) {
                        switch ($childNode->nodeName) {
                            case 'Group':
                            {
                                $newGroup = new Group();
                                $newGroup->parse($childNode);
                                $this->AddItem($newGroup);
                                break;
                            }
                            case 'Question':
                            {
                                $newQuestion = new Question();
                                $newQuestion->parse($childNode);
                                $this->AddItem($newQuestion);
                                break;
                            }
                            case 'Label':
                            {
                                $newLabel = new Label();
                                $newLabel->parse($childNode);
                                $this->AddItem($newLabel);
                                break;
                            }
                            case 'Button':
                            {
                                $newButton = new Button();
                                $newButton->parse($childNode);
                                $this->AddItem($newButton);
                                break;
                            }
                            default:
                            {
                                break;
                            }
                        }
                    }
                    break;
                }
                default:
                {
                    echo "Group Not implemented yet:" . $item->nodeName . "<br>";
                    break;
                }
            }
        }
    }
}
