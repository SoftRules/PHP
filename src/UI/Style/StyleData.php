<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Style;

final readonly class StyleData
{
    public function __construct(
        public string $class,
        public string $inlineStyle = '',
    ) {
        //
    }
}
