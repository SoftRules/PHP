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

    public static function bootstrap3(): self
    {
        return new self(
            page: new StyleData(class: 'col-xs-12'),
            // TODO add other group types
        );
    }
}
