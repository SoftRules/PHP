<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use SoftRules\PHP\Enums\eGroupType;

interface GroupComponentInterface extends ComponentWithCustomProperties, UiComponentInterface
{
    public function setGroupID(string $groupID): void;

    public function getGroupID(): string;

    public function setName(string $name): void;

    public function getName(): string;

    public function setType(eGroupType|string $type): void;

    public function getType(): eGroupType;

    public function setUpdateUserInterface($updateUserInterface): void;

    public function getUpdateUserInterface();

    public function setPageID(string $pageID): void;

    public function getPageID(): string;

    public function setSuppressItemsWhenInvisible($suppressItemsWhenInvisible): void;

    public function getSuppressItemsWhenInvisible();

    public function addHeaderItem($headerItem): void;

    public function getHeaderItems(): array;

    public function setParameter(ParameterInterface $parameter): void;

    public function getParameter(): ParameterInterface;

    public function shouldHavePagination(): bool;

    public function shouldHaveNextPageButton(): bool;

    public function shouldHavePreviousPageButton(): bool;
}
