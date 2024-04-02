<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DOMDocument;
use DOMElement;
use DOMXPath;
use SoftRules\PHP\Contracts\UI\ConditionContract;
use SoftRules\PHP\Contracts\UI\OperandContract;
use SoftRules\PHP\Enums\eLogOperator;
use SoftRules\PHP\Enums\eOperator;
use SoftRules\PHP\Enums\eValueType;
use SoftRules\PHP\Traits\ParsedFromXml;
use SoftRules\PHP\UI\Collections\UiComponentsCollection;
use SoftRules\PHP\UI\Components\Group;
use SoftRules\PHP\UI\Components\Question;
use Throwable;

class Condition implements ConditionContract
{
    use ParsedFromXml;

    private ?eLogOperator $logOperator = null;

    private eOperator $operator;

    private OperandContract $leftOperand;

    private OperandContract $rightOperand;

    public function setLogOperator(eLogOperator|string $logOperator): void
    {
        if ($logOperator instanceof eLogOperator) {
            $this->logOperator = $logOperator;

            return;
        }

        $this->logOperator = eLogOperator::from($logOperator);
    }

    public function getLogOperator(): ?eLogOperator
    {
        return $this->logOperator;
    }

    public function setOperator(eOperator|string $operator): void
    {
        if ($operator instanceof eOperator) {
            $this->operator = $operator;

            return;
        }

        $this->operator = eOperator::from($operator);
    }

    public function getOperator(): eOperator
    {
        return $this->operator;
    }

    public function setLeftOperand(OperandContract $leftOperand): void
    {
        $this->leftOperand = $leftOperand;
    }

    public function getLeftOperand(): OperandContract
    {
        return $this->leftOperand;
    }

    public function setRightOperand(OperandContract $rightOperand): void
    {
        $this->rightOperand = $rightOperand;
    }

    public function getRightOperand(): OperandContract
    {
        return $this->rightOperand;
    }

    public function copyFrom($sourceCondition): void
    {
        //
    }

    public function getElement(UiComponentsCollection $components, OperandContract $operand, DOMDocument $UserInterfaceData): mixed
    {
        foreach ($components as $component) {
            if ($component instanceof Question) {
                if ($component->getName() == $operand->getValue()) {
                    //Tussen accolades staat de instantie van de node in het xml. Staat er niets, dan impliceert dat de eerste instantie zijnde {1}
                    //Door {1} te vervangen door niets, kunnen de strings met elkaar vergeleken worden.
                    $itemPath = str_replace('{1}', '', (string) $component->getElementPath());
                    $operandPath = str_replace('{1}', '', $operand->getElementPath());

                    if ($itemPath === $operandPath) {
                        return $component->getValue();
                    }
                }
            } elseif ($component instanceof Group) {
                $return = $this->getElement($component->getComponents(), $operand, $UserInterfaceData);

                if ($return !== '') {
                    return $return;
                }
            }
        }

        $xpath = new DOMXpath($UserInterfaceData);
        $operandPath = str_replace(['{', '}'], ['[', ']'], $operand->getElementPath());

        $nodes = $xpath->query("/{$operandPath}/{$operand->getValue()}");
        if ($nodes->length > 0) {
            return $nodes->item(0)->nodeValue;
        }

        return '';
    }

    public function value($status, UiComponentsCollection $components, DOMDocument $UserInterfaceData): bool
    {
        $res = false;
        $processed = false;

        if ($this->getLeftOperand()->getValueType() === eValueType::ELEMENTNAME) {
            $lfs = $this->getElement($components, $this->getLeftOperand(), $UserInterfaceData);
        } else {
            $lfs = $this->getLeftOperand()->getValue();
        }

        if ($this->getRightOperand()->getValueType() === eValueType::ELEMENTNAME) {
            $rfs = $this->getElement($components, $this->getRightOperand(), $UserInterfaceData);
        } else {
            $rfs = $this->getRightOperand()->getValue();
        }

        $lf = $this->convertInt($lfs);
        $rf = $this->convertInt($rfs);

        if ($lf !== null && $rf !== null) {
            $processed = true;
            switch ($this->getOperator()) {
                case eOperator::EQUAL:
                    $res = $lf === $rf;
                    break;
                case eOperator::GREATER_EQUAL:
                    $res = ($lf >= $rf);
                    break;
                case eOperator::LESS_EQUAL:
                    $res = ($lf <= $rf);
                    break;
                case eOperator::NOT_EQUAL:
                    $res = ($lf !== $rf);
                    break;
                case eOperator::GREATER:
                    $res = ($lf > $rf);
                    break;
                case eOperator::LESS:
                    $res = ($lf < $rf);
                    break;
                case eOperator::IN:
                    $options = str_split((string) $rfs);
                    foreach ($options as $option) {
                        if ($option == $lfs) {
                            $res = true;
                            break;
                        }
                    }

                    break;
                default:
                    echo 'Operator not implemented yet:' . $this->getOperator()->value . '<br>';
                    break;
            }
        }

        if (! $processed) {
            $ld = $this->convertDate($lfs);
            $rd = $this->convertDate($rfs);

            if ($ld && $rd) {
                $processed = true;
                switch ($this->getOperator()) {
                    case eOperator::EQUAL:
                        $res = $ld->isSameDay($rd);
                        break;
                    case eOperator::GREATER_EQUAL:
                        $res = $ld->greaterThanOrEqualTo($rd);
                        break;
                    case eOperator::LESS_EQUAL:
                        $res = $ld->lessThanOrEqualTo($rd);
                        break;
                    case eOperator::NOT_EQUAL:
                        $res = ! $ld->isSameDay($rd);
                        break;
                    case eOperator::GREATER:
                        $res = $ld->greaterThan($rd);
                        break;
                    case eOperator::LESS:
                        $res = $ld->lessThan($rd);
                        break;
                    case eOperator::IN:
                        $res = false;
                        $options = str_split((string) $rfs);
                        // TODO: double check these date comparisons?
                        foreach ($options as $option) {
                            if ($option == $lfs) {
                                $res = true;
                                break;
                            }
                        }

                        break;
                    default:
                        echo 'Operator not implemented yet:' . $this->getOperator()->value . '<br>';
                        break;
                }
            }
        }

        if (! $processed) {
            // treat both operands as strings
            switch ($this->getOperator()) {
                case eOperator::EQUAL:
                    $res = ((string) $lfs === (string) $rfs);
                    break;
                case eOperator::GREATER_EQUAL:
                    $res = ($lfs >= $rfs);
                    break;
                case eOperator::LESS_EQUAL:
                    $res = ($lfs <= $rfs);
                    break;
                case eOperator::NOT_EQUAL:
                    $res = ((string) $lfs !== (string) $rfs);
                    break;
                case eOperator::IN:
                    $res = false;
                    $options = str_split((string) $rfs);
                    foreach ($options as $option) {
                        if ($option == $lfs) {
                            $res = true;
                            break;
                        }
                    }

                    break;
                case eOperator::GREATER:
                    $res = ($lfs > $rfs);
                    break;
                case eOperator::LESS:
                    $res = ($lfs < $rfs);
                    break;
            }
        }

        return match ($this->getLogOperator()) {
            eLogOperator::AND => $res && $status,
            eLogOperator::OR => $res || $status,
            default => $status,
        };
    }

    private function convertInt(mixed $value): ?int
    {
        if (! is_numeric($value)) {
            return null;
        }

        // alleen deze waarde mag 0 zijn
        if ($value === '0') {
            return 0;
        }

        $int = (int) $value;

        if ($int === 0) {
            return null;
        }

        return $int;
    }

    public function convertDate(mixed $value): ?CarbonInterface
    {
        if ($value instanceof CarbonInterface) {
            return $value;
        }

        if (! is_string($value)) {
            return null;
        }

        $formats = ['dd-MM-yyyy'];
        foreach ($formats as $f) {
            try {
                return Carbon::createFromFormat($f, $value);
            } catch (Throwable) {
                //
            }
        }

        return null;
    }

    public function writeXml($writer): void
    {
        //
    }

    public function parse(DOMElement $DOMElement): static
    {
        foreach ($DOMElement->childNodes as $childNode) {
            switch ($childNode->nodeName) {
                case 'LogOperator':
                    $this->setLogOperator($childNode->nodeValue);
                    break;
                case 'Operator':
                    $this->setOperator($childNode->nodeValue);
                    break;
                case 'LeftOperand':
                    $this->setLeftOperand(Operand::createFromDomNode($childNode));
                    break;
                case 'RightOperand':
                    $this->setRightOperand(Operand::createFromDomNode($childNode));
                    break;
            }
        }

        return $this;
    }
}
