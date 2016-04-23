<?php

/**
 * This file is part of adam.
 *
 * (c) David Weichert <info@davidweichert.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Type;


class Exercise
{
    /**
     * @var int
     */
    private $number;

    /**
     * @var int
     */
    private $operand1;

    /**
     * @var int
     */
    private $operand2;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var int
     */
    private $result;

    /**
     * @var int
     */
    private $proposed;

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return Exercise
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return int
     */
    public function getOperand1()
    {
        return $this->operand1;
    }

    /**
     * @param int $operand1
     * @return Exercise
     */
    public function setOperand1($operand1)
    {
        $this->operand1 = $operand1;
        return $this;
    }

    /**
     * @return int
     */
    public function getOperand2()
    {
        return $this->operand2;
    }

    /**
     * @param int $operand2
     * @return Exercise
     */
    public function setOperand2($operand2)
    {
        $this->operand2 = $operand2;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     * @return Exercise
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param int $result
     * @return Exercise
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return int
     */
    public function getProposed()
    {
        return $this->proposed;
    }

    /**
     * @param int $proposed
     * @return Exercise
     */
    public function setProposed($proposed)
    {
        $this->proposed = $proposed;
        return $this;
    }
    
}
