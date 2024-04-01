<?php declare(strict_types=1);

namespace SoftRules\PHP\Contracts\UI\Components;

use SoftRules\PHP\Contracts\UI\ComponentWithCustomPropertiesContract;
use SoftRules\PHP\Contracts\UI\ParameterContract;
use SoftRules\PHP\Contracts\UI\UiComponentContract;
use SoftRules\PHP\Enums\eGroupType;

interface GroupComponentContractProperties extends ComponentWithCustomPropertiesContract, UiComponentContract
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

    public function setParameter(ParameterContract $parameter): void;

    public function getParameter(): ParameterContract;

    public function shouldHavePagination(): bool;

    public function shouldHaveNextPageButton(): bool;

    public function shouldHavePreviousPageButton(): bool;
}
