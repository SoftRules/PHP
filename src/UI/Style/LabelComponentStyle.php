<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Style;

final class LabelComponentStyle implements UiComponentStyle
{
    public function __construct(
        public readonly StyleData $default,
        // TODO add other group types
    ) {
        //
    }

    public static function bootstrapThree(): self
    {
        return new self(
            default: new StyleData(class: 'control-label'),
            // TODO add other group types
        );
    }
}
