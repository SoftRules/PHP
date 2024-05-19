<?php declare(strict_types=1);

namespace SoftRules\PHP;

use DOMDocument;
use SoftRules\PHP\Contracts\RenderableWrapper;
use SoftRules\PHP\Contracts\UI\Components\ButtonComponentContract;
use SoftRules\PHP\Contracts\UI\Components\GroupComponentContract;
use SoftRules\PHP\Contracts\UI\Components\LabelComponentContract;
use SoftRules\PHP\Contracts\UI\Components\QuestionComponentContract;
use SoftRules\PHP\Contracts\UI\UiComponentContract;
use SoftRules\PHP\Enums\eDefaultState;
use SoftRules\PHP\Enums\eGroupType;
use SoftRules\PHP\UI\Action;
use SoftRules\PHP\UI\Collections\ActionCollection;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;
use SoftRules\PHP\UI\SoftRulesFormData;
use Stringable;

final class EvaluateExpressions implements Stringable
{
    private string $html = '';

    private int $currentPage;

    public readonly int $totalPages;

    public readonly DOMDocument $userInterfaceData;
    private readonly UiComponentsCollection $allComponents;
    public readonly ActionCollection $actionList;

    public function __construct(SoftRulesFormData $UIClass)
    {
        $this->actionList = new ActionCollection();
        $this->userInterfaceData = $UIClass->getUserInterfaceData();
        $this->allComponents = $UIClass->components;
        $this->EvaluateExpressions($UIClass->components);
    }

    private function EvaluateExpressions(UiComponentsCollection $components): void
    {
        foreach ($components as $component) {

            if ($component instanceof GroupComponentContract) {
                //Visible expression
                if ($component->getType() !== eGroupType::page) {
                    if ($this->itemVisible($component)) {
                        $this->actionList->add(new Action($component->getGroupID(), 'Show', ''));
                    } else {
                        $this->actionList->add(new Action($component->getGroupID(), 'Hide', ''));
                    }
                }

                if ($component instanceof RenderableWrapper) {
                    $this->EvaluateExpressions($component->getComponents());
                }

            } elseif ($component instanceof QuestionComponentContract) {
                //Visible expression
                $visible = $this->itemVisible($component);
                if ($visible) {
                    $this->actionList->add(new Action($component->getQuestionID(), 'Show', ''));
                } else {
                    $this->actionList->add(new Action($component->getQuestionID(), 'Hide', ''));
                }

                //Required expression (only when visible)
                $required = $component->getRequiredExpression()->value($this->allComponents, $this->userInterfaceData);
                if (($required) && ($visible)) {
                    $this->actionList->add(new Action($component->getQuestionID(), 'Required', ''));
                } else {
                    $this->actionList->add(new Action($component->getQuestionID(), 'NotRequired', ''));
                }

                //Valid expression (only when visible)
                $valid = $component->getValidExpression()->value($this->allComponents, $this->userInterfaceData);
                if (($valid) && ($visible)) {
                    $this->actionList->add(new Action($component->getQuestionID(), 'Valid', ''));
                } else {
                    $this->actionList->add(new Action($component->getQuestionID(), 'Invalid', ''));
                }

                //Enabled expression
                if ($this->itemEnabled($component)) {
                    $this->actionList->add(new Action($component->getQuestionID(), 'Enabled', ''));
                } else {
                    $this->actionList->add(new Action($component->getQuestionID(), 'Disabled', ''));
                }

            } elseif ($component instanceof LabelComponentContract) {
                //Visible expression
                if ($this->itemVisible($component)) {
                    $this->actionList->add(new Action($component->getLabelID(), 'Show', ''));
                } else {
                    $this->actionList->add(new Action($component->getLabelID(), 'Hide', ''));
                }

            } elseif ($component instanceof ButtonComponentContract) {
                //Visible expression
                if ($this->itemVisible($component)) {
                    $this->actionList->add(new Action($component->getButtonID(), 'Show', ''));
                } else {
                    $this->actionList->add(new Action($component->getButtonID(), 'Hide', ''));
                }
            }
        }
    }

    public function itemVisible(UiComponentContract $component): bool
    {
        return $component->getVisibleExpression()->value($this->allComponents, $this->userInterfaceData);
    }

    public function itemEnabled(QuestionComponentContract $component): bool
    {
        $defaultstateExpression = $component->getDefaultStateExpression()->value($this->allComponents, $this->userInterfaceData);
        if ($component->getDefaultState() === eDefaultState::Readonly) {
            if ($defaultstateExpression) {
                return false;
            } else {
                return true;
            }

        } elseif ($component->getDefaultState() === eDefaultState::Editable) {
            if ($defaultstateExpression) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    public function __toString(): string
    {
        return '';
    }
}