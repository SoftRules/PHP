<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Style;

final class GroupComponentStyle implements UiComponentStyle
{
    public function __construct(
        public readonly StyleData $page,
        // TODO add other group types
    ) {
        //
    }

    public static function bootstrapThree(): self
    {
        return new self(
            page: new StyleData(class: 'full-width'),
            // TODO add other group types
        );
    }
}
