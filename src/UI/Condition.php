<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DOMDocument;
use DOMNode;
use DOMXPath;
use Illuminate\Support\Collection;
use SoftRules\PHP\Enums\eLogOperator;
use SoftRules\PHP\Enums\eOperator;
use SoftRules\PHP\Enums\eValueType;
use SoftRules\PHP\Interfaces\BaseItemInterface;
use SoftRules\PHP\Interfaces\ConditionInterface;
use SoftRules\PHP\Interfaces\OperandInterface;
use SoftRules\PHP\Traits\ParsedFromXml;
use Throwable;

class Condition implements ConditionInterface
{
    use ParsedFromXml;

    private ?eLogOperator $logOperator = null;
    private eOperator $operator;
    private OperandInterface $leftOperand;
    private OperandInterface $rightOperand;

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

    public function setLeftOperand(OperandInterface $leftOperand): void
    {
        $this->leftOperand = $leftOperand;
    }

    public function getLeftOperand(): OperandInterface
    {
        return $this->leftOperand;
    }

    public function setRightOperand(OperandInterface $rightOperand): void
    {
        $this->rightOperand = $rightOperand;
    }

    public function getRightOperand(): OperandInterface
    {
        return $this->rightOperand;
    }

    public function copyFrom($sourceCondition): void
    {
        //
    }

    /**
     * @param Collection<int, BaseItemInterface> $items
     */
    public function getElement(Collection $items, OperandInterface $operand, DOMDocument $UserInterfaceData): mixed
    {
        foreach ($items as $item) {
            if ($item instanceof Question) {
                if ($item->getName() == $operand->getValue()) {
                    //Tussen accolades staat de instantie van de node in het xml. Staat er niets, dan impliceert dat de eerste instantie zijnde {1}
                    //Door {1} te vervangen door niets, kunnen de strings met elkaar vergeleken worden.
                    $itempath = str_replace('{1}', '', (string) $item->getElementPath());
                    $operandpath = str_replace('{1}', '', $operand->getElementPath());

                    if ($itempath === $operandpath) {
                        return $item->getValue();
                    }
                }
            } elseif ($item instanceof Group) {
                $return = $this->getElement($item->getItems(), $operand, $UserInterfaceData);

                if ($return !== '') {
                    return $return;
                }
            }
        }

        $operandPath = $operand->getElementPath();
        $xpath = new DOMXpath($UserInterfaceData);
        $operandPath = str_replace(['{', '}'], ['[', ']'], $operandPath);

        $nodes = $xpath->query('/' . $operandPath . '/' . $operand->getValue());
        if ($nodes->length > 0) {
            return $nodes->item(0)->nodeValue;
        }

        return '';
    }

    /**
     * @param Collection<int, BaseItemInterface> $items
     */
    public function value($status, Collection $items, DOMDocument $UserInterfaceData): bool
    {
        $res = false;
        $processed = false;

        if ($this->getLeftOperand()->getValueType() === eValueType::ELEMENTNAME) {
            $lfs = $this->getElement($items, $this->getLeftOperand(), $UserInterfaceData);
        } else {
            $lfs = $this->getLeftOperand()->getValue();
        }

        if ($this->getRightOperand()->getValueType() === eValueType::ELEMENTNAME) {
            $rfs = $this->getElement($items, $this->getRightOperand(), $UserInterfaceData);
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
                    $res = ($lf != $rf);
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

    public function parse(DOMNode $node): self
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case 'LogOperator':
                    $this->setLogOperator($item->nodeValue);
                    break;
                case 'Operator':
                    $this->setOperator($item->nodeValue);
                    break;
                case 'LeftOperand':
                    $this->setLeftOperand(Operand::createFromDomNode($item));
                    break;
                case 'RightOperand':
                    $this->setRightOperand(Operand::createFromDomNode($item));
                    break;
            }
        }

        return $this;
    }
}
