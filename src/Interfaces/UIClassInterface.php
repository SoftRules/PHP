<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use DOMDocument;

interface UIClassInterface
{
    public function addItem(BaseItemInterface $item): void;

    public function setConfigID($configID): void;

    public function getConfigID();

    public function setUserInterfaceID($userInterfaceID): void;

    public function getUserInterfaceID();

    public function setSoftRulesXml(DOMDocument $softRulesXml): void;

    public function getSoftRulesXml(): DOMDocument;

    public function setState(string $state): void;

    public function getState(): string;

    public function setPage(int $page): void;

    public function getPage(): int;

    public function setPages(int $pages): void;

    public function getPages(): int;

    public function setUserInterfaceData(DOMDocument $userInterfaceData): void;

    public function getUserInterfaceData(): DOMDocument;

    public function setSessionID(string $sessionID): void;

    public function getSessionID(): string;

    public function itemVisible(array $items, BaseItemInterface $item, DOMDocument $userInterfaceData): bool;

    public function itemValid(array $items, BaseItemInterface $item, DOMDocument $userInterfaceData): bool;

    public function itemRequired(array $items, BaseItemInterface $item, DOMDocument $userInterfaceData): bool;

    public function itemEnabled(array $items, BaseItemInterface $item, DOMDocument $userInterfaceData): bool;
}
