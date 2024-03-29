<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMDocument;
use DOMNameSpaceNode;
use DOMNode;
use Illuminate\Support\Collection;
use SoftRules\PHP\Interfaces\ISoftRules_Base;
use SoftRules\PHP\Interfaces\IUIClass;

class UIClass implements IUIClass
{
    private DOMDocument $softRulesXml;
    private string $state = '';
    private int $page = 1;
    private int $pages = 1;
    private DOMDocument $userinterfaceData;
    private string $sessionID;
    private $configID;
    private $userInterfaceID;
    /**
     * @var Collection<int, ISoftRules_Base>
     */
    public readonly Collection $items;

    public function __construct()
    {
        $this->items = new Collection();
        $this->userinterfaceData = new DOMDocument();
        $this->softRulesXml = new DOMDocument();
    }

    public function setSoftRulesXml(DOMDocument $softRulesXml): void
    {
        $this->softRulesXml = $softRulesXml;
    }

    public function getSoftRulesXml(): DOMDocument
    {
        return $this->softRulesXml;
    }

    public function setConfigID($configID): void
    {
        $this->configID = $configID;
    }

    public function getConfigID()
    {
        return $this->configID;
    }

    public function setUserInterfaceID($userInterfaceID): void
    {
        $this->userInterfaceID = $userInterfaceID;
    }

    public function getUserInterfaceID()
    {
        return $this->userInterfaceID;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPages(int $pages): void
    {
        $this->pages = $pages;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function setUserinterfaceData(DOMDocument $userinterfaceData): void
    {
        $this->userinterfaceData = $userinterfaceData;
    }

    public function getUserinterfaceData(): DOMDocument
    {
        return $this->userinterfaceData;
    }

    public function setSessionID(string $sessionID): void
    {
        $this->sessionID = $sessionID;
    }

    public function getSessionID(): string
    {
        return $this->sessionID;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function addItem(ISoftRules_Base $item): void
    {
        $this->items->add($item);
    }

    public function itemVisible(array $items, ISoftRules_Base $item, DOMDocument $userinterfaceData): bool
    {
        return true;
    }

    public function itemValid(array $items, ISoftRules_Base $item, DOMDocument $userinterfaceData): bool
    {
        return true;
    }

    public function itemRequired(array $items, ISoftRules_Base $item, DOMDocument $userinterfaceData): bool
    {
        return true;
    }

    public function itemEnabled(array $items, ISoftRules_Base $item, DOMDocument $userinterfaceData): bool
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
                    $xml = new DOMDocument();
                    $xml->loadXML($child->nodeValue);
                    $this->setSoftRulesXml($xml);
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
                        $innerHTML = '';
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
                    foreach ($child->childNodes as $item) {
                        switch ($item->nodeName) {
                            case 'Group':
                                $this->addItem(Group::createFromDomNode($item));
                                break;
                            case 'Question':
                                $this->addItem(Question::createFromDomNode($item));
                                break;
                            case 'Label':
                                $this->addItem(Label::createFromDomNode($item));
                                break;
                            case 'Button':
                                $this->addItem(Button::createFromDomNode($item));
                                break;
                            default:
                                break;
                        }
                    }
                    break;
                default:
                    echo 'ParseUI Not implemented yet:' . $child->nodeName . '<br>';
                    break;
            }
        }
    }
}
