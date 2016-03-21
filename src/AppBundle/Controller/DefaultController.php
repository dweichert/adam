<?php

namespace AppBundle\Controller;

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

        var_dump($request);die;

        return $this->render($view);
    }

    /**
     * @Route("/")
     */
    public function redirectAction()
    {
        return $this->redirectToRoute('homepage', ['_locale' => 'en'], 301);
    }
}
