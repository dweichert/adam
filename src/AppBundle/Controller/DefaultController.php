<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        $addSubRange = $this->getRange($request->get('addSubRange'), 0, 250, [0, 100]);
        $mulDivRange = $this->getRange($request->get('mulDivRange'), 0, 100, [0, 12]);
        $exercises = $this->getExercises($request->get('numberOfExercises'), 30);

        $game = new Game();
        $game
            ->setPlayerName(strlen($request->get('playerName')) ? substr($request->get('playerName'), 0, 255) : 'Anonymous')
            ->setAddition((bool) $request->get('addition'))
            ->setSubtraction((bool) $request->get('subtraction'))
            ->setMultiplication((bool) $request->get('multiplication'))
            ->setDivision((bool) $request->get('division'))
            ->setAddSubFrom($addSubRange[0])
            ->setAddSubTo($addSubRange[1])
            ->setMulDivFrom($mulDivRange[0])
            ->setMulDivTo($mulDivRange[1])
            ->setExercises($exercises)
            ->setTimeLimit($timeLimit)
            ->setStart(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($game);
        $em->flush();

        return $this->render($view);
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
        if (!is_numeric($param))
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
        if (!is_numeric($components[0]))
        {
            return $default;
        }
        if (!is_numeric($components[1]))
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
    public function getExercises($param, $default)
    {
        if (!is_numeric($param))
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
}
