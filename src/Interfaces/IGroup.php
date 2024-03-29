<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

use SoftRules\PHP\Enums\eGroupType;
use SoftRules\PHP\UI\Parameter;

interface IGroup extends ISoftRules_Base, ItemWithCustomProperties
{
    public function setGroupID($GroupID): void;

    public function getGroupID();

    public function setName($Name): void;

    public function getName();

    public function setType(eGroupType|string $Type): void;

    public function getType(): eGroupType;

    public function setUpdateUserinterface($UpdateUserinterface): void;

    public function getUpdateUserinterface();

    public function setPageID($pageID): void;

    public function getPageID();

    public function setSuppressItemsWhenInvisible($SuppressItemsWhenInvisible): void;

    public function getSuppressItemsWhenInvisible();

    public function setHeaderItems(array $HeaderItems): void;

    public function getHeaderItems(): array;

    public function setParameter(Parameter $Parameter): void;

    public function getParameter(): Parameter;

    public function shouldHavePagination(): bool;

    public function shouldHaveNextPageButton(): bool;

    public function shouldHavePreviousPageButton(): bool;
}
