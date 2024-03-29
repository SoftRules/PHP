<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use DOMDocument;

interface IUIClass
{
    public function addItem(ISoftRules_Base $item): void;

    public function setConfigID($configID): void;

    public function getConfigID();

    public function setUserinterfaceID($userInterfaceID): void;

    public function getUserinterfaceID();

    public function setSoftRulesXml(DOMDocument $softRulesXml): void;

    public function getSoftRulesXml(): DOMDocument;

    public function setState(string $state): void;

    public function getState(): string;

    public function setPage(int $page): void;

    public function getPage(): int;

    public function setPages(int $pages): void;

    public function getPages(): int;

    public function setUserinterfaceData(DOMDocument $userinterfaceData): void;

    public function getUserinterfaceData(): DOMDocument;

    public function setSessionID(string $sessionID): void;

    public function getSessionID(): string;

    public function itemVisible(array $items, ISoftRules_Base $item, DOMDocument $userinterfaceData): bool;

    public function itemValid(array $items, ISoftRules_Base $item, DOMDocument $userinterfaceData): bool;

    public function itemRequired(array $items, ISoftRules_Base $item, DOMDocument $userinterfaceData): bool;

    public function itemEnabled(array $items, ISoftRules_Base $item, DOMDocument $userinterfaceData): bool;
}
