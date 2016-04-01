<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 */
class Game
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="playerName", type="string", length=255)
     */
    private $playerName;

    /**
     * @var bool
     *
     * @ORM\Column(name="addition", type="boolean")
     */
    private $addition;

    /**
     * @var bool
     *
     * @ORM\Column(name="subtraction", type="boolean")
     */
    private $subtraction;

    /**
     * @var bool
     *
     * @ORM\Column(name="multiplication", type="boolean")
     */
    private $multiplication;

    /**
     * @var bool
     *
     * @ORM\Column(name="division", type="boolean")
     */
    private $division;

    /**
     * @var int
     *
     * @ORM\Column(name="addSubFrom", type="integer")
     */
    private $addSubFrom;

    /**
     * @var int
     *
     * @ORM\Column(name="addSubTo", type="integer")
     */
    private $addSubTo;

    /**
     * @var int
     *
     * @ORM\Column(name="mulDivFrom", type="integer")
     */
    private $mulDivFrom;

    /**
     * @var int
     *
     * @ORM\Column(name="mulDivTo", type="integer")
     */
    private $mulDivTo;

    /**
     * @var int
     *
     * @ORM\Column(name="timeLimit", type="integer")
     */
    private $timeLimit;

    /**
     * @var int
     *
     * @ORM\Column(name="elapsed", type="integer")
     */
    private $elapsed;

    /**
     * @var bool
     *
     *
     * @ORM\Column(name="multiplication", type="boolean")
     */
    private $timeLimitExceeded;

    /**
     * @var int
     *
     * @ORM\Column(name="exercises", type="integer")
     */
    private $exercises;

    /**
     * @var int
     *
     * @ORM\Column(name="correct", type="integer", nullable=true)
     */
    private $correct;

    /**
     * @var int
     *
     * @ORM\Column(name="incorrect", type="integer", nullable=true)
     */
    private $incorrect;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetimetz")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finish", type="datetimetz", nullable=true)
     */
    private $finish;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set playerName
     *
     * @param string $playerName
     *
     * @return Game
     */
    public function setPlayerName($playerName)
    {
        $this->playerName = $playerName;

        return $this;
    }

    /**
     * Get playerName
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->playerName;
    }

    /**
     * Set addition
     *
     * @param boolean $addition
     *
     * @return Game
     */
    public function setAddition($addition)
    {
        $this->addition = $addition;

        return $this;
    }

    /**
     * Get addition
     *
     * @return bool
     */
    public function getAddition()
    {
        return $this->addition;
    }

    /**
     * Set subtraction
     *
     * @param boolean $subtraction
     *
     * @return Game
     */
    public function setSubtraction($subtraction)
    {
        $this->subtraction = $subtraction;

        return $this;
    }

    /**
     * Get subtraction
     *
     * @return bool
     */
    public function getSubtraction()
    {
        return $this->subtraction;
    }

    /**
     * Set multiplication
     *
     * @param boolean $multiplication
     *
     * @return Game
     */
    public function setMultiplication($multiplication)
    {
        $this->multiplication = $multiplication;

        return $this;
    }

    /**
     * Get multiplication
     *
     * @return bool
     */
    public function getMultiplication()
    {
        return $this->multiplication;
    }

    /**
     * Set division
     *
     * @param boolean $division
     *
     * @return Game
     */
    public function setDivision($division)
    {
        $this->division = $division;

        return $this;
    }

    /**
     * Get division
     *
     * @return bool
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * Set addSubFrom
     *
     * @param integer $addSubFrom
     *
     * @return Game
     */
    public function setAddSubFrom($addSubFrom)
    {
        $this->addSubFrom = $addSubFrom;

        return $this;
    }

    /**
     * Get addSubFrom
     *
     * @return int
     */
    public function getAddSubFrom()
    {
        return $this->addSubFrom;
    }

    /**
     * Set addSubTo
     *
     * @param integer $addSubTo
     *
     * @return Game
     */
    public function setAddSubTo($addSubTo)
    {
        $this->addSubTo = $addSubTo;

        return $this;
    }

    /**
     * Get addSubTo
     *
     * @return int
     */
    public function getAddSubTo()
    {
        return $this->addSubTo;
    }

    /**
     * Set mulDivFrom
     *
     * @param integer $mulDivFrom
     *
     * @return Game
     */
    public function setMulDivFrom($mulDivFrom)
    {
        $this->mulDivFrom = $mulDivFrom;

        return $this;
    }

    /**
     * Get mulDivFrom
     *
     * @return int
     */
    public function getMulDivFrom()
    {
        return $this->mulDivFrom;
    }

    /**
     * Set mulDivTo
     *
     * @param integer $mulDivTo
     *
     * @return Game
     */
    public function setMulDivTo($mulDivTo)
    {
        $this->mulDivTo = $mulDivTo;

        return $this;
    }

    /**
     * Get mulDivTo
     *
     * @return int
     */
    public function getMulDivTo()
    {
        return $this->mulDivTo;
    }

    /**
     * Set timeLimit
     *
     * @param integer $timeLimit
     *
     * @return Game
     */
    public function setTimeLimit($timeLimit)
    {
        $this->timeLimit = $timeLimit;

        return $this;
    }

    /**
     * Get timeLimit
     *
     * @return int
     */
    public function getTimeLimit()
    {
        return $this->timeLimit;
    }

    /**
     * Get elapsed
     *
     * @return int
     */
    public function getElapsed()
    {
        return $this->elapsed;
    }

    /**
     * Set elapsed
     *
     * @param $elapsed
     *
     * @return Game
     */
    public function setElapsed($elapsed)
    {
        $this->elapsed = $elapsed;

        return $this;
    }

    /**
     * Is time limit exceeded
     *
     * @return bool
     */
    public function isTimeLimitExceeded()
    {
        return $this->timeLimitExceeded;
    }

    /**
     * Set time limit exceeded
     *
     * @param $timeLimitExceeded
     *
     * @return Game
     */
    public function setTimeLimitExceeded($timeLimitExceeded)
    {
        $this->timeLimitExceeded = $timeLimitExceeded;

        return $this;
    }

    /**
     * Set exercises
     *
     * @param integer $exercises
     *
     * @return Game
     */
    public function setExercises($exercises)
    {
        $this->exercises = $exercises;

        return $this;
    }

    /**
     * Get exercises
     *
     * @return int
     */
    public function getExercises()
    {
        return $this->exercises;
    }

    /**
     * Set correct
     *
     * @param integer $correct
     *
     * @return Game
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;

        return $this;
    }

    /**
     * Get correct
     *
     * @return int
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * Set incorrect
     *
     * @param integer $incorrect
     *
     * @return Game
     */
    public function setIncorrect($incorrect)
    {
        $this->incorrect = $incorrect;

        return $this;
    }

    /**
     * Get incorrect
     *
     * @return int
     */
    public function getIncorrect()
    {
        return $this->incorrect;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     *
     * @return Game
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set finish
     *
     * @param \DateTime $finish
     *
     * @return Game
     */
    public function setFinish($finish)
    {
        $this->finish = $finish;

        return $this;
    }

    /**
     * Get finish
     *
     * @return \DateTime
     */
    public function getFinish()
    {
        return $this->finish;
    }
}

