<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use SoftRules\PHP\Interfaces\RestrictionsInterface;

class Restrictions implements RestrictionsInterface
{
    private $enumerationValues;
    private $fractionDigits;
    private $length;
    private $maxExclusive;
    private $minExclusive;
    private $maxInclusive;
    private $minInclusive;
    private $maxLength;
    private $minLength;
    private $pattern;
    private $totalDigits;
    private $whiteSpace;

    public function setEnumerationValues($enumerationValues): void
    {
        $this->enumerationValues = $enumerationValues;
    }

    public function getEnumerationValues()
    {
        return $this->enumerationValues;
    }

    public function setFractionDigits($fractionDigits): void
    {
        $this->fractionDigits = $fractionDigits;
    }

    public function getFractionDigits()
    {
        return $this->fractionDigits;
    }

    public function setLength($length): void
    {
        $this->length = $length;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function setMaxExclusive($maxExclusive): void
    {
        $this->maxExclusive = $maxExclusive;
    }

    public function getMaxExclusive()
    {
        return $this->maxExclusive;
    }

    public function setMinExclusive($minExclusive): void
    {
        $this->minExclusive = $minExclusive;
    }

    public function getMinExclusive()
    {
        return $this->minExclusive;
    }

    public function setMaxInclusive($maxInclusive): void
    {
        $this->maxInclusive = $maxInclusive;
    }

    public function getMaxInclusive()
    {
        return $this->maxInclusive;
    }

    public function setMinInclusive($minInclusive): void
    {
        $this->minInclusive = $minInclusive;
    }

    public function getMinInclusive()
    {
        return $this->minInclusive;
    }

    public function setMaxLength($maxLength): void
    {
        $this->maxLength = $maxLength;
    }

    public function getMaxLength()
    {
        return $this->maxLength;
    }

    public function setMinLength($minLength): void
    {
        $this->minLength = $minLength;
    }

    public function getMinLength()
    {
        return $this->minLength;
    }

    public function setPattern($pattern): void
    {
        $this->pattern = $pattern;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function setTotalDigits($totalDigits): void
    {
        $this->totalDigits = $totalDigits;
    }

    public function getTotalDigits()
    {
        return $this->totalDigits;
    }

    public function setWhiteSpace($whiteSpace): void
    {
        $this->whiteSpace = $whiteSpace;
    }

    public function getWhiteSpace()
    {
        return $this->whiteSpace;
    }

    public function Valid($question): bool
    {
        return true;
    }
}
