<?php

namespace SoftRules\PHP\Interfaces;

use DOMDocument;

interface IUIClass
{
    public function setItems(array $Items): void;

    public function getItems(): array;

    public function AddItem(ISoftRules_Base $item);

    public function setConfigID($ConfigID): void;

    public function getConfigID();

    public function setUserinterfaceID($UserInterfaceID): void;

    public function getUserinterfaceID();

    public function setSoftRulesXml(DOMDocument $SoftRulesXml): void;

    public function getSoftRulesXml(): DOMDocument;

    public function setState(string $State): void;

    public function getState(): string;

    public function setPage(int $Page): void;

    public function getPage(): int;

    public function setPages(int $Pages): void;

    public function getPages(): int;

    public function setUserinterfaceData(DOMDocument $UserinterfaceData): void;

    public function getUserinterfaceData(): DOMDocument;

    public function setSessionID(string $SessionID): void;

    public function getSessionID(): string;

    public function ItemVisible(array $Items, $item, DOMDocument $userinterfaceData): bool;

    public function ItemValid(array $Items, $item, DOMDocument $userinterfaceData): bool;

    public function ItemRequired(array $Items, $item, DOMDocument $userinterfaceData): bool;

    public function ItemEnabled(array $Items, $item, DOMDocument $userinterfaceData): bool;
}

