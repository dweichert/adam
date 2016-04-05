<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;
use \DateTimeZone;

class DefaultController extends Controller
{
    /**
     * @Route("/{_locale}", requirements={"_locale" = "en|de"}, name="homepage")
     */
    public function indexAction(Request $request)
    {
        $locale = $request->getLocale();
        $view = "default/$locale/index.html.twig";

        return $this->render($view);
    }

    /**
     * @Route("/{_locale}/play", requirements={"_locale" = "en|de"}, name="play")
     */
    public function playAction(Request $request)
    {
        $locale = $request->getLocale();
        $view = "default/$locale/play.html.twig";
        $timeLimit = $this->getTimeLimit($request->get('timeLimit'), 3);
        $name = strlen($request->get('playerName')) ? substr($request->get('playerName'), 0, 255) : '';
        $time = ['start' => time(), 'limit' => $timeLimit];
        $addSubRange = $this->getRange($request->get('addSubRange'), 0, 250, [0, 100]);
        $mulDivRange = $this->getRange($request->get('mulDivRange'), 0, 100, [0, 12]);
        $numberOfExercises = $this->getNumberOfExercises($request->get('numberOfExercises'), 30);

        $gameId = $this->saveGame($request, $name, $time, $addSubRange, $mulDivRange, $numberOfExercises);

        return $this->render(
            $view,
            [
                'name' => $name,
                'timeLimit' => $timeLimit <= 5 ? true : false,
                'minutes' => $timeLimit,
                'seconds' => 0,
                'showTimeLimit' => (bool)$request->get('showTimeLimit'),
                'exercises' => $this->getExercises($request, $addSubRange, $mulDivRange, $numberOfExercises),
                'id' => $gameId,
                'token' => sha1($gameId . $this->getParameter('secret'))
            ]
        );
    }

    /**
     * @Route("/{_locale}/score", requirements={"_locale" = "en|de"}, name="score")
     */
    public function scoreAction(Request $request)
    {
        $locale = $request->getLocale();
        $view = "default/$locale/score.html.twig";
        $gameId = $request->get('id');
        $hash = sha1($gameId . $this->getParameter('secret'));
        $idIntegrity = $hash == $request->get('token');

        if ($idIntegrity)
        {
            $correct = 0;
            $incorrect = 0;
            $exercises = [];
            $i = 1;
            while ($i) {
                $operand1 = $request->get('operand1-' . $i);
                if (is_null($operand1))
                {
                    break;
                }
                $operand2 = $request->get('operand2-' . $i);
                $operator = $request->get('operator-' . $i);
                $result = $request->get('result-' . $i);
                $proposed = $request->get('solution-' . $i);
                $exercises[] = $this->checkExercise($operand1, $operand2, $operator, $result, $proposed, $correct, $incorrect);
                $i++;
            }

            var_dump($exercises);
            var_dump($correct);
            var_dump($incorrect);
            var_dump($request->request->all());
        }

        return $this->render(
            $view,
            [
                'idIntegrity' => $idIntegrity,
            ]
        );
    }

    /**
     * @Route("/")
     */
    public function redirectAction()
    {
        return $this->redirectToRoute('homepage', ['_locale' => 'en'], 301);
    }

    /**
     * @param mixed $param
     * @param int $default
     * @return int
     */
    private function getTimeLimit($param, $default)
    {
        if (!$this->isIntegerish($param))
        {
            return $default;
        }
        if (3 > $param)
        {
            return 1;
        }
        if (3 == $param)
        {
            return 3;
        }
        if (5 >= $param)
        {
            return 5;
        }

        return 52560000;
    }

    /**
     * @param mixed $param
     * @param int $min
     * @param int $max
     * @param int[] $default
     * @return int[]
     */
    private function getRange($param, $min, $max, $default)
    {
        $components = explode(',', $param);
        if (2 != count($components))
        {
            return $default;
        }
        if (!$this->isIntegerish($components[0]))
        {
            return $default;
        }
        if (!$this->isIntegerish($components[1]))
        {
            return $default;
        }
        if ($components[0] < $min)
        {
            return $default;
        }
        if ($components[0] > $max)
        {
            return $default;
        }
        if ($components[1] < $min)
        {
            return $default;
        }
        if ($components[1] > $max)
        {
            return $default;
        }
        if ($components[0] > $components[1])
        {
            return $default;
        }

        return [(int) $components[0], (int) $components[1]];
    }

    /**
     * @param mixed $param
     * @param int $default
     * @return int
     */
    private function getNumberOfExercises($param, $default)
    {
        if (!$this->isIntegerish($param))
        {
            return $default;
        }
        if (6 >= $param)
        {
            return 6;
        }
        if (12 >= $param)
        {
            return 12;
        }
        if (30 >= $param)
        {
            return 30;
        }
        if (60 >= $param)
        {
            return 60;
        }

        return 100;
    }

    /**
     * @param Request $request
     * @return mixed[]
     */
    private function getExercises(Request $request, $addSubRange, $mulDivRange, $numberOfExercises)
    {
        $exercises = [];
        $typeOfExercises = [];
        if ($request->get('addition'))
        {
            $typeOfExercises[] = 'addition';
        }
        if ($request->get('subtraction'))
        {
            $typeOfExercises[] = 'subtraction';
        }
        if ($request->get('multiplication'))
        {
            $typeOfExercises[] = 'multiplication';
        }
        if ($request->get('division'))
        {
            $typeOfExercises[] = 'division';
        }
        if (empty($typeOfExercises))
        {
            $typeOfExercises = ['addition', 'subtraction', 'multiplication', 'division'];
        }
        for ($i = 1; $i <= $numberOfExercises; $i++)
        {
            $maxIndex = count($typeOfExercises) - 1;
            $type = $typeOfExercises[rand(0, $maxIndex)];
            if (in_array($type, ['addtion', 'subtraction']))
            {
                $range = $addSubRange;
            }
            else
            {
                $range = $mulDivRange;
            }
            $method = 'get' . ucfirst($type);
            list($operand1, $operand2, $operator, $result) = $this->$method($range);
            $hidden = rand(0,2);
            switch ($hidden)
            {
                case 0:
                    $operand1 = '?';
                    break;
                case 1:
                    $operand2 = '?';
                    break;
                default:
                    $result = '?';
                    break;
            }
            $exercises[] = [
                'number' => $i,
                'operand1' => $operand1,
                'operand2' => $operand2,
                'operator' => $operator,
                'result' => $result
            ];
        }

        return $exercises;
    }

    /**
     * @param int[] $range
     * @return mixed[]
     */
    private function getAddition($range)
    {
        $operand1 = rand($range[0], $range[1]);
        $operand2 = rand($range[0], $range[1]);
        $operator = '+';
        $result = $operand1 + $operand2;

        return [$operand1, $operand2, $operator, $result];
    }

    /**
     * @param int[] $range
     * @return mixed[]
     */
    private function getSubtraction($range)
    {
        $value1 = rand($range[0], $range[1]);
        $value2 = rand($range[0], $range[1]);

        $operand1 = $value1 >= $value2 ? $value1 : $value2;
        $operand2 = $value1 <= $value2 ? $value1 : $value2;
        $operator = '-';
        $result = $operand1 - $operand2;

        return [$operand1, $operand2, $operator, $result];
    }

    /**
     * @param int[] $range
     * @return mixed[]
     */
    private function getMultiplication($range)
    {
        $operand1 = rand($range[0], $range[1]);
        $operand2 = rand($range[0], $range[1]);
        $operator = 'x';
        $result = $operand1 * $operand2;

        return [$operand1, $operand2, $operator, $result];
    }

    /**
     * @param int[] $range
     * @return mixed[]
     */
    private function getDivision($range)
    {
        $value1 = rand($range[0], $range[1]);
        $value2 = rand($range[0], $range[1]);
        if ($value2 == 0)
        {
            $value2 = 1;
        }
        $operand1 = $value1 * $value2;
        $operand2 = $value2;
        $operator = ':';
        $result = $value1;

        return [$operand1, $operand2, $operator, $result];
    }

    /**
     * @param int $operand1
     * @param int $operand2
     * @param string $operator
     * @param int $result
     * @param int $proposed
     * @param int &$correct
     * @param int &$incorrect
     * @return string
     */
    private function checkExercise($operand1, $operand2, $operator, $result, $proposed, &$correct, &$incorrect)
    {
        $missing = array_search('?', ['operand1' => $operand1, 'operand2' => $operand2, 'result' => $result]);
        switch ($missing)
        {
            case 'operand1':
                $expected = $operand1 = $this->calculateOperand1($result, $operator, $operand2);
                $given = sprintf('%d %s %d = %d', $proposed, $operator, $operand2, $result);
                break;
            case 'operand2':
                $expected = $operand2 = $this->calculateOperand2($result, $operator, $operand1);
                $given = sprintf('%d %s %d = %d', $operand1, $operator, $proposed, $result);
                break;
            case 'result':
                $expected = $result = $this->calculateResult($operand1, $operator, $operand2);
                $given = sprintf('%d %s %d = %d', $operand1, $operator, $operand2, $proposed);
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

        if ($expected == $proposed)
        {
            $evaluates = 'correct';
            $correct++;
        }
        else
        {
            $evaluates = 'incorrect';
            $incorrect++;
        }

        return '<div class="exercise ' . $evaluates .'">'
            . '<span class="correct">' . $solution . '</span>'
            . '<span class="given">' . $given . '</span>'
            . '</div>';
    }

    /**
     * @param int $result
     * @param string $operator
     * @param int $operand2
     * @return bool|int
     */
    private function calculateOperand1($result, $operator, $operand2)
    {
        if (!$this->isIntegerish($result) && !$this->isIntegerish($operand2))
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
     * @return bool|int
     */
    private function calculateOperand2($result, $operator, $operand1)
    {
        if (!$this->isIntegerish($result) && !$this->isIntegerish($operand1))
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
                return (int) $result / $operand1;
                break;
            case ':':
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
        if (!$this->isIntegerish($operand1) && !$this->isIntegerish($operand2))
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

    /**
     * @param mixed $value
     * @return bool
     */
    private function isIntegerish($value)
    {
        if (ctype_digit((string)$value))
        {
            return true;
        }

        return false;
    }

    /**
     * @param Request $request
     * @param string $name
     * @param mixed[] $time
     * @param int[] $addSubRange
     * @param int[] $mulDivRange
     * @param int $numberOfExercises
     * @return int
     */
    private function saveGame(Request $request, $name, $time, $addSubRange, $mulDivRange, $numberOfExercises)
    {
        $date = new DateTime();
        $date
            ->setTimezone(new DateTimeZone('UTC'))
            ->setTimestamp($time['start']);
        $game = new Game();
        $game
            ->setPlayerName($name ?: 'Anonymous')
            ->setStart($date)
            ->setTimeLimit($time['limit'])
            ->setAddition((bool) $request->get('addition'))
            ->setSubtraction((bool) $request->get('subtraction'))
            ->setMultiplication((bool) $request->get('multiplication'))
            ->setDivision((bool) $request->get('division'))
            ->setAddSubFrom($addSubRange[0])
            ->setAddSubTo($addSubRange[1])
            ->setMulDivFrom($mulDivRange[0])
            ->setMulDivTo($mulDivRange[1])
            ->setExercises($numberOfExercises);

        $em = $this->getDoctrine()->getManager();
        $em->persist($game);
        $em->flush();

        return $game->getId();
    }

    private function updateGame($id)
    {
//        $game = new Game();
//        $game
//            ->setPlayerName($name)
//            ->setAddition((bool) $request->get('addition'))
//            ->setSubtraction((bool) $request->get('subtraction'))
//            ->setMultiplication((bool) $request->get('multiplication'))
//            ->setDivision((bool) $request->get('division'))
//            ->setAddSubFrom($addSubRange[0])
//            ->setAddSubTo($addSubRange[1])
//            ->setMulDivFrom($mulDivRange[0])
//            ->setMulDivTo($mulDivRange[1])
//            ->setExercises($exercises)
//            ->setTimeLimit($timeLimit)
//            ->setTimeLimitExceeded()
//            ->setStart(new \DateTime())
//            ->setFinish()
//            ->setCorrect()
//            ->setIncorrect();
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($game);
//        $em->flush();
    }
}
