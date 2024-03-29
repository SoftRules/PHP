<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

interface RestrictionsInterface
{
    public function setEnumerationValues($enumerationValues): void;

    public function getEnumerationValues();

    public function setFractionDigits($fractionDigits): void;

    public function getFractionDigits();

    public function setLength($length): void;

    public function getLength();

    public function setMaxExclusive($maxExclusive): void;

    public function getMaxExclusive();

    public function setMinExclusive($minExclusive): void;

    public function getMinExclusive();

    public function setMaxInclusive($maxInclusive): void;

    public function getMaxInclusive();

    public function setMinInclusive($minInclusive): void;

    public function getMinInclusive();

    public function setMaxLength($maxLength): void;

    public function getMaxLength();

    public function setMinLength($minLength): void;

    public function getMinLength();

    public function setPattern($pattern): void;

    public function getPattern();

    public function setTotalDigits($totalDigits): void;

    public function getTotalDigits();

    public function setWhiteSpace($whiteSpace): void;

    public function getWhiteSpace();

    public function Valid($question): bool;
}
