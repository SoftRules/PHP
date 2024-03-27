<?php declare(strict_types=1);

namespace SoftRules\PHP\Interfaces;

interface ITextValues
{
    public function setTextValuesID($textValuesID): void;

    public function getTextValuesID();

    public function setTextQuestionID($textQuestionID): void;

    public function getTextQuestionID();

    public function setTextValuesList($textValuesList): void;
}