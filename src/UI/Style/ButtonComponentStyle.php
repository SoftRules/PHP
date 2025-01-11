<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Style;

final readonly class ButtonComponentStyle implements UiComponentStyle
{
    public function __construct(
        public StyleData $default,
        public StyleData $primary,
        public StyleData $success,
        public StyleData $danger,
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
