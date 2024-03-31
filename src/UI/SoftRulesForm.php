<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use DOMDocument;
use DOMNameSpaceNode;
use DOMNode;
use SoftRules\PHP\Interfaces\SoftRulesFormInterface;
use SoftRules\PHP\Interfaces\UiComponentInterface;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;
use SoftRules\PHP\UI\Components\Button;
use SoftRules\PHP\UI\Components\Group;
use SoftRules\PHP\UI\Components\Label;
use SoftRules\PHP\UI\Components\Question;

final class SoftRulesForm implements SoftRulesFormInterface
{
    private DOMDocument $softRulesXml;

    private string $state = '';

    private int $page = 1;

    private int $pages = 1;

    private DOMDocument $userInterfaceData;

    private string $sessionID;

    private string $configID;

    private string $userInterfaceID;

    public readonly UiComponentsCollection $components;

    private function __construct()
    {
        $this->components = new UiComponentsCollection();
        $this->userInterfaceData = new DOMDocument();
        $this->softRulesXml = new DOMDocument();
    }

    public static function fromXmlString(string $xmlString): self
    {
        $xml = new DOMDocument();
        $xml->loadXML($xmlString);

        return self::fromDomDocument($xml);
    }

    public static function fromDomDocument(DOMDocument $xml): self
    {
        return (new self())->parseUIXML($xml);
    }

    public function setSoftRulesXml(DOMDocument $softRulesXml): void
    {
        $this->softRulesXml = $softRulesXml;
    }

    public function getSoftRulesXml(): DOMDocument
    {
        return $this->softRulesXml;
    }

    public function setConfigID(string $configID): void
    {
        $this->configID = $configID;
    }

    public function getConfigID(): string
    {
        return $this->configID;
    }

    public function setUserInterfaceID(string $userInterfaceID): void
    {
        $this->userInterfaceID = $userInterfaceID;
    }

    public function getUserInterfaceID(): string
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

    public function setUserInterfaceData(DOMDocument $userInterfaceData): void
    {
        $this->userInterfaceData = $userInterfaceData;
    }

    public function getUserInterfaceData(): DOMDocument
    {
        return $this->userInterfaceData;
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

    public function addComponent(UiComponentInterface $component): void
    {
        $this->components->add($component);
    }

    public function componentVisible(UiComponentInterface $component, DOMDocument $userInterfaceData): bool
    {
        return true;
    }

    public function componentValid(UiComponentInterface $component, DOMDocument $userInterfaceData): bool
    {
        return true;
    }

    public function itemRequired(UiComponentInterface $component, DOMDocument $userInterfaceData): bool
    {
        return true;
    }

    public function itemEnabled(UiComponentInterface $component, DOMDocument $userInterfaceData): bool
    {
        return true;
    }

    public function parseUIXML(DOMDocument $xml): self
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
                    $this->setSessionID((string) $child->nodeValue);
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

                        $this->setUserInterfaceData($xml);
                    }

                    break;
                case 'Questions': //this tag contains child elements, of which we only want one.
                    foreach ($child->childNodes as $childNode) {
                        switch ($childNode->nodeName) {
                            case 'Group':
                                $this->addComponent(Group::createFromDomNode($childNode));
                                break;
                            case 'Question':
                                $this->addComponent(Question::createFromDomNode($childNode));
                                break;
                            case 'Label':
                                $this->addComponent(Label::createFromDomNode($childNode));
                                break;
                            case 'Button':
                                $this->addComponent(Button::createFromDomNode($childNode));
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

        return $this;
    }
}
