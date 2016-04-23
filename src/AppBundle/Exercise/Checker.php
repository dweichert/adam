<?php
/**
 * This file is part of adam.
 *
 * (c) David Weichert <info@davidweichert.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Exercise;

use AppBundle\Assertion\Type;
use AppBundle\Type\Exercise;

class Checker
{
    /**
     * @var Type
     */
    private $assertionType;

    public function __construct(Type $type)
    {
        $this->assertionType = $type;
    }

    /**
     * @param Exercise $exercise
     * @param int &$correct
     * @param int &$incorrect
     * @return string
     */
    public function checkExercise(Exercise $exercise, &$correct, &$incorrect)
    {
        $operand1 = $exercise->getOperand1();
        $operand2 = $exercise->getOperand2();
        $operator = $exercise->getOperator();
        $result = $exercise->getResult();
        $proposed = $exercise->getProposed();

        if (!$this->assertionType->isIntegerish($proposed))
        {
            $proposed = 'NOT A NUMBER';
        }
        $missing = array_search('?', ['operand1' => $operand1, 'operand2' => $operand2, 'result' => $result]);
        switch ($missing)
        {
            case 'operand1':
                $expected = $operand1 = $this->calculateOperand1($result, $operator, $operand2, $proposed);
                $given = sprintf('%s %s %d = %d', $proposed, $operator, $operand2, $result);
                break;
            case 'operand2':
                $expected = $operand2 = $this->calculateOperand2($result, $operator, $operand1, $proposed);
                $given = sprintf('%d %s %s = %d', $operand1, $operator, $proposed, $result);
                break;
            case 'result':
                $expected = $result = $this->calculateResult($operand1, $operator, $operand2);
                $given = sprintf('%d %s %d = %s', $operand1, $operator, $operand2, $proposed);
                break;
            default:
                $expected = false;
                $given = '';
                break;
        }

        if (false === $expected)
        {
            return '<div class="exercise invalid"></div>';
        }

        $solution = sprintf('%d %s %d = %d', $operand1, $operator, $operand2, $result);

        if ($expected == $proposed && $this->assertionType->isIntegerish($proposed))
        {
            $evaluates = 'correct';
            $bg = 'bg-success';
            $result = '<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>';
            $correct++;
        }
        else
        {
            $evaluates = 'incorrect';
            $bg = 'bg-danger';
            $result = '<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>';
            $incorrect++;
        }

        return '<div class="exercise ' . $evaluates . ' ' . $bg . '">'
            . $result
            . ' <span class="correct">' . $solution . '</span>'
            . ' <span class="given">' . $given . '</span>'
            . '</div>';
    }
    
    /**
     * @param int $result
     * @param string $operator
     * @param int $operand2
     * @param int $proposed
     * @return bool|int
     */
    private function calculateOperand1($result, $operator, $operand2, $proposed)
    {
        if (!$this->assertionType->isIntegerish($result) && !$this->assertionType->isIntegerish($operand2))
        {
            return false;
        }
        switch ($operator)
        {
            case '+':
                return (int) $result - $operand2;
                break;
            case '-':
                return (int) $result + $operand2;
                break;
            case 'x':
                if ($operand2 == 0)
                {
                    return (int) $this->assertionType->isIntegerish($proposed) ? $proposed : 1;
                }
                return (int) $result / $operand2;
                break;
            case ':':
                return (int) $result * $operand2;
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * @param int $result
     * @param string $operator
     * @param int $operand1
     * @param int $proposed
     * @return bool|int
     */
    private function calculateOperand2($result, $operator, $operand1, $proposed)
    {
        if (!$this->assertionType->isIntegerish($result) && !$this->assertionType->isIntegerish($operand1))
        {
            return false;
        }
        switch ($operator)
        {
            case '+':
                return (int) $result - $operand1;
                break;
            case '-':
                return (int) $operand1 - $result;
                break;
            case 'x':
                if ($operand1 == 0)
                {
                    return (int) $this->assertionType->isIntegerish($proposed) ? $proposed : 1;
                }
                return (int) $result / $operand1;
                break;
            case ':':
                if ($result == 0)
                {
                    return (int) $this->assertionType->isIntegerishAndNotZero($proposed) ? $proposed : 1;
                }
                return (int) $operand1 / $result;
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * @param int $operand1
     * @param string $operator
     * @param int $operand2
     * @return bool|int
     */
    private function calculateResult($operand1, $operator, $operand2)
    {
        if (!$this->assertionType->isIntegerish($operand1) && !$this->assertionType->isIntegerish($operand2))
        {
            return false;
        }
        switch ($operator)
        {
            case '+':
                return (int) $operand1 + $operand2;
                break;
            case '-':
                return (int) $operand1 - $operand2;
                break;
            case 'x':
                return (int) $operand1 * $operand2;
                break;
            case ':':
                return (int) $operand1 / $operand2;
                break;
            default:
                return false;
                break;
        }
    }
}
