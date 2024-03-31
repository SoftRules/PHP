<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

interface LabelComponentInterface extends UiComponentInterface
{
    public function setLabelID(string $labelID): void;

    public function getLabelID(): string;

    public function setText(string $text): void;

    public function getText(): string;

    public function setParameter(ParameterInterface $parameter): void;

    public function getParameter(): ParameterInterface;
}
