<?php

namespace ChalkBoardEducation\Ui\Bundle\AdminBundle\Action;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class Home
{
    /** @var EngineInterface */
    private $templating;

    /**
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * @return Response
     */
    public function __invoke()
    {
        return $this->templating->renderResponse('AdminBundle:Home:index.html.twig');
    }
}
