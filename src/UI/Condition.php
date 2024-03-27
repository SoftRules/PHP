<?php declare(strict_types=1);

namespace SoftRules\PHP\UI;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DOMDocument;
use DOMNode;
use DOMXPath;
use SoftRules\PHP\Enums\eLogOperator;
use SoftRules\PHP\Enums\eOperator;
use SoftRules\PHP\Enums\eValueType;
use SoftRules\PHP\Interfaces\ICondition;
use SoftRules\PHP\Interfaces\IOperand;
use SoftRules\PHP\Interfaces\ISoftRules_Base;
use Throwable;

class Condition implements ICondition
{
    private ?eLogOperator $logoperator = null;
    private eOperator $operator;
    private IOperand $left_operand;
    private IOperand $right_operand;

    public function setLogoperator(eLogOperator|string $logoperator): void
    {
        if ($logoperator instanceof eLogOperator) {
            $this->logoperator = $logoperator;
            return;
        }

        $this->logoperator = eLogOperator::from($logoperator);
    }

    public function getLogoperator(): ?eLogOperator
    {
        return $this->logoperator;
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

    public function setLeft_operand(IOperand $left_operand): void
    {
        $this->left_operand = $left_operand;
    }

    public function getLeft_operand(): IOperand
    {
        return $this->left_operand;
    }

    public function setRight_operand(IOperand $right_operand): void
    {
        $this->right_operand = $right_operand;
    }

    public function getRight_operand(): IOperand
    {
        return $this->right_operand;
    }

    public function CopyFrom($sourceCondition): void
    {
        //
    }

    /**
     * @param ISoftRules_Base[] $Items
     */
    public function getElement(array $Items, IOperand $operand, DOMDocument $UserinterfaceData): mixed
    {
        foreach ($Items as $item) {
            if ($item instanceof Question) {
                if ($item->getName() == $operand->getValue()) {
                    //Tussen accolades staat de instantie van de node in het xml. Staat er niets, dan impliceert dat de eerste instantie zijnde {1}
                    //Door {1} te vervangen door niets, kunnen de strings met elkaar vergeleken worden.
                    $itempath = str_replace("{1}", "", $item->getElementPath());
                    $operandpath = str_replace("{1}", "", $operand->getElementPath());

                    if ($itempath === $operandpath) {
                        return $item->getValue();
                    }
                }
            } elseif ($item instanceof Group) {
                $return = $this->getElement($item->getItems(), $operand, $UserinterfaceData);

                if ($return !== "") {
                    return $return;
                }
            }
        }

        $operandPath = $operand->getElementPath();
        $xpath = new DOMXpath($UserinterfaceData);
        $operandPath = str_replace(['{', '}'], ['[', ']'], $operandPath);

        $nodes = $xpath->query("/" . $operandPath . "/" . $operand->getValue());
        if ($nodes->length > 0) {
            return $nodes->item(0)->nodeValue;
        }

        return "";
    }

    /**
     * @param ISoftRules_Base[] $Items
     */
    public function Value($status, array $Items, DOMDocument $UserinterfaceData): bool
    {
        $res = false;
        $processed = false;

        if ($this->getLeft_operand()->getValueType() === eValueType::ELEMENTNAME) {
            $lfs = $this->getElement($Items, $this->getLeft_operand(), $UserinterfaceData);
        } else {
            $lfs = $this->getLeft_operand()->getValue();
        }

        if ($this->getRight_operand()->getValueType() === eValueType::ELEMENTNAME) {
            $rfs = $this->getElement($Items, $this->getRight_operand(), $UserinterfaceData);
        } else {
            $rfs = $this->getRight_operand()->getValue();
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
                    $options = str_split($rfs);
                    foreach ($options as $option) {
                        if ($option == $lfs) {
                            $res = true;
                            break;
                        }
                    }
                    break;
                default:
                    echo "Operator not implemented yet:" . $this->getOperator()->value . "<br>";
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
                        $options = str_split($rfs);
                        // TODO: double check these date comparisons?
                        foreach ($options as $option) {
                            if ($option == $lfs) {
                                $res = true;
                                break;
                            }
                        }
                        break;
                    default:
                        echo "Operator not implemented yet:" . $this->getOperator()->value . "<br>";
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
                    $options = str_split($rfs);
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

        return match ($this->getLogoperator()) {
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
        if ($value === "0") {
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

    public function WriteXml($writer): void
    {
        //
    }

    public function parse(DOMNode $node): void
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case 'LogOperator':
                    $this->setLogoperator($item->nodeValue);
                    break;
                case 'Operator':
                    $this->setOperator($item->nodeValue);
                    break;
                case 'LeftOperand':
                    $operand = new Operand();
                    $operand->parse($item);
                    $this->setLeft_operand($operand);
                    break;
                case 'RightOperand':
                    $operand = new Operand();
                    $operand->parse($item);
                    $this->setRight_operand($operand);
                    break;
            }
        }
    }
}
