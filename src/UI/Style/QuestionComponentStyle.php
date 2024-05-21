<?php declare(strict_types=1);

namespace SoftRules\PHP\UI\Style;

final class QuestionComponentStyle implements UiComponentStyle
{
    public function __construct(
        public readonly StyleData $default,
        public readonly StyleData $slider,
        // TODO add specific question types
    ) {
        //
    }

    public static function bootstrapThree(): self
    {
        return new self(
            default: new StyleData(class: 'form-control'),
            slider: new StyleData(class: ''),
            // TODO add specific question types
        );
    }
}
