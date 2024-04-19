<?php declare(strict_types=1);

namespace SoftRules\PHP;

use DOMDocument;
use SoftRules\PHP\Contracts\Renderable;
use SoftRules\PHP\Contracts\RenderableWrapper;
use SoftRules\PHP\Contracts\UI\Components\ButtonComponentContract;
use SoftRules\PHP\Contracts\UI\Components\GroupComponentContract;
use SoftRules\PHP\Contracts\UI\Components\LabelComponentContract;
use SoftRules\PHP\Contracts\UI\Components\QuestionComponentContract;
use SoftRules\PHP\Contracts\UI\UiComponentContract;
use SoftRules\PHP\Enums\eGroupType;
use SoftRules\PHP\UI\Collections\ActionCollection;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;
use SoftRules\PHP\UI\SoftRulesFormData;
use SoftRules\PHP\UI\Action;
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
                if ($component->getType() !== eGroupType::page) {
                    if ($this->itemVisible($component)) {
                        $this->actionList->add(new Action($component->getGroupID(), "Show", ""));
                    } else {
                        $this->actionList->add(new Action($component->getGroupID(), "Hide", ""));
                    }
                }

                if ($component instanceof RenderableWrapper) {
                    $this->EvaluateExpressions($component->getComponents());
                }
               
            } elseif ($component instanceof QuestionComponentContract) {
                if ($this->itemVisible($component)) {
                    $this->actionList->add(new Action($component->getQuestionID(), "Show", ""));
                } else {
                    $this->actionList->add(new Action($component->getQuestionID(), "Hide", ""));
                }
            } elseif ($component instanceof LabelComponentContract) {
                if ($this->itemVisible($component)) {
                    $this->actionList->add(new Action($component->getLabelID(), "Show", ""));
                } else {
                    $this->actionList->add(new Action($component->getLabelID(), "Hide", ""));
                }

            } elseif ($component instanceof ButtonComponentContract) {
                if ($this->itemVisible($component)) {
                    $this->actionList->add(new Action($component->getButtonID(), "Show", ""));
                } else {
                    $this->actionList->add(new Action($component->getButtonID(), "Hide", ""));
                }

            }
        }
    }
    
    public function itemVisible(UiComponentContract $component): bool
    {
        return $component->getVisibleExpression()->value($this->allComponents, $this->userInterfaceData);
    }
  
   
    public function __toString(): string
    {
        return $this->actionList->toJson(JSON_PRETTY_PRINT);
    }
}
