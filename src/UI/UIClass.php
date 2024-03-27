<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMDocument;
use DOMElement;
use DOMNameSpaceNode;
use DOMNode;
use SoftRules\PHP\Interfaces\ISoftRules_Base;
use SoftRules\PHP\Interfaces\IUIClass;

class UIClass implements IUIClass
{
    private DOMDocument $SoftRulesXml;
    private string $State = "";
    private int $Page = 1;
    private int $Pages = 1;
    private DOMDocument $UserinterfaceData;
    private string $SessionID;
    private $ConfigID;
    private $UserInterfaceID;
    /**
     * @var ISoftRules_Base[]
     */
    private array $Items = [];

    public function __construct()
    {
        $this->UserinterfaceData = new DOMDocument();
        $this->SoftRulesXml = new DOMDocument();
    }

    public function setSoftRulesXml(DOMDocument $SoftRulesXml): void
    {
        $this->SoftRulesXml = new DOMDocument();
        $this->SoftRulesXml->loadXML($SoftRulesXml->saveHTML());
    }

    public function getSoftRulesXml(): DOMDocument
    {
        return $this->SoftRulesXml;
    }

    public function setConfigID($ConfigID): void
    {
        $this->ConfigID = $ConfigID;
    }

    public function getConfigID()
    {
        return $this->ConfigID;
    }

    public function setUserInterfaceID($UserInterfaceID): void
    {
        $this->UserInterfaceID = $UserInterfaceID;
    }

    public function getUserInterfaceID()
    {
        return $this->UserInterfaceID;
    }

    public function setPage(int $Page): void
    {
        $this->Page = $Page;
    }

    public function getPage(): int
    {
        return $this->Page;
    }

    public function setPages(int $Pages): void
    {
        $this->Pages = $Pages;
    }

    public function getPages(): int
    {
        return $this->Pages;
    }

    public function setUserinterfaceData(DOMDocument $UserinterfaceData): void
    {
        $this->UserinterfaceData = $UserinterfaceData;
    }

    public function getUserinterfaceData(): DOMDocument
    {
        return $this->UserinterfaceData;
    }

    public function setSessionID(string $SessionID): void
    {
        $this->SessionID = $SessionID;
    }

    public function getSessionID(): string
    {
        return $this->SessionID;
    }

    public function setState(string $State): void
    {
        $this->State = $State;
    }

    public function getState(): string
    {
        return $this->State;
    }

    /**
     * @param ISoftRules_Base[] $Items
     */
    public function setItems(array $Items): void
    {
        $this->Items = $Items;
    }

    /**
     * @return ISoftRules_Base[]
     */
    public function getItems(): array
    {
        return $this->Items;
    }

    public function AddItem(ISoftRules_Base $item): void
    {
        $this->Items[] = $item;
    }

    public function ItemVisible(array $Items, $item, DOMDocument $userinterfaceData): bool
    {
        return true;
    }

    public function ItemValid(array $Items, $item, DOMDocument $userinterfaceData): bool
    {
        return true;
    }

    public function ItemRequired(array $Items, $item, DOMDocument $userinterfaceData): bool
    {
        return true;
    }

    public function ItemEnabled(array $Items, $item, DOMDocument $userinterfaceData): bool
    {
        return true;
    }

    public function ParseUIXML(DOMDocument $xml): void
    {
        $x = $xml->documentElement;
        /** @var DOMNode|DOMNameSpaceNode $child */
        foreach ($x->childNodes as $child) {
            switch ($child->nodeName) {
                case 'Page':
                    $this->setPage((int) $child->nodeValue);
                    break;
                case 'Pages':
                    $this->setPages((int) $child->nodeValue);
                    break;
                case 'SessionID':
                    $this->setSessionID($child->nodeValue);
                    break;
                case 'SoftRulesXml':
                    $this->setSoftRulesXml($child->nodeValue);
                    break;
                case 'ConfigID':
                    $this->setConfigID($child->nodeValue);
                    break;
                case 'UserInterfaceID':
                    $this->setUserInterfaceID($child->nodeValue);
                    break;
                case 'State':
                    $this->setState((string) $child->nodeValue);
                    break;
                case 'SoftRulesXML':
                    break;
                case 'UserinterfaceData':
                    if ($child->childNodes->length > 0) {
                        $innerHTML = "";
                        $children = $child->childNodes;

                        foreach ($children as $innerChild) {
                            $innerHTML .= $innerChild->ownerDocument->saveHTML($innerChild);
                        }

                        $xml = new DOMDocument();
                        $xml->loadXML($innerHTML);

                        $this->setUserinterfaceData($xml);
                    }

                    break;
                case 'Questions': //this tag contains child elements, of which we only want one.
                {
                    foreach ($child->childNodes as $Item) {
                        switch ($Item->nodeName) {
                            case 'Group':
                            {
                                $newGroup = new Group();
                                $newGroup->parse($Item);
                                $this->AddItem($newGroup);
                                break;
                            }
                            case 'Question':
                            {
                                $newQuestion = new Question();
                                $newQuestion->parse($Item);
                                $this->AddItem($newQuestion);
                                break;
                            }
                            case 'Label':
                            {
                                $newLabel = new Label();
                                $newLabel->parse($Item);
                                $this->AddItem($newLabel);
                                break;
                            }
                            case 'Button':
                            {
                                $newButton = new Button();
                                $newButton->parse($Item);
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
                    echo "ParseUI Not implemented yet:" . $child->nodeName . "<br>";
                    break;
                }
            }
        }
    }
}
