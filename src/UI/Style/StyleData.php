<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Style;

final class StyleData
{
    public function __construct(
        public readonly string $class,
        public readonly string $inlineStyle = '',
    ) {
        //
    }
}
