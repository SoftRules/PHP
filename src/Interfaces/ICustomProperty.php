<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

interface ICustomProperty
{
    public function setName($Name): void;

    public function getName();

    public function setValue($Value): void;

    public function getValue();

    public function parse($item);
}