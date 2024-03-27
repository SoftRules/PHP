<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

interface ITextValueItem
{
    public function setValue($Value): void;

    public function getValue();

    public function setText($Text): void;

    public function getText();

    public function getImageUrl();

    public function setImageUrl($ImageUrl): void;

    public function writeXml($writer);
}