<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts;

use DOMDocument;
use SoftRules\PHP\Contracts\UI\UiComponentContract;

interface SoftRulesFormContract
{
    public function addComponent(UiComponentContract $component): void;

    public function setConfigID(string $configID): void;

    public function getConfigID(): string;

    public function setUserInterfaceID(string $userInterfaceID): void;

    public function getUserInterfaceID(): string;

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

    public function componentVisible(UiComponentContract $component, DOMDocument $userInterfaceData): bool;

    public function componentValid(UiComponentContract $component, DOMDocument $userInterfaceData): bool;

    public function itemRequired(UiComponentContract $component, DOMDocument $userInterfaceData): bool;

    public function itemEnabled(UiComponentContract $component, DOMDocument $userInterfaceData): bool;
}
