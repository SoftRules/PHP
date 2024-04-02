<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Style;

final class ButtonComponentStyle implements UiComponentStyle
{
    public function __construct(
        public readonly StyleData $default,
        public readonly StyleData $primary,
        public readonly StyleData $success,
        public readonly StyleData $danger,
    ) {
        //
    }

    public static function bootstrapThree(): self
    {
        return new self(
            default: new StyleData(class: 'btn btn-default'),
            primary: new StyleData(class: 'btn btn-primary'),
            success: new StyleData(class: 'btn btn-success'),
            danger: new StyleData(class: 'btn btn-danger'),
        );
    }
}
