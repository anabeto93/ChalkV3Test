<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 28/02/2018
 * Time: 12:56
 */

namespace App\Ui\Admin\Action\Institution;


use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Institution\InstitutionListQuery;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ListAction {
    /** @var EngineInterface */
    private $engine;

    /** @var QueryBusInterface */
    private $queryBus;

    /**
     * ListAction constructor.
     * @param EngineInterface $engine
     * @param QueryBusInterface $queryBus
     */
    public function __construct(EngineInterface $engine, QueryBusInterface $queryBus) {
        $this->engine = $engine;
        $this->queryBus = $queryBus;
    }

    /**
     * @return Response
     */
    public function __invoke(): Response {
        $institutions = $this->queryBus->handle(new InstitutionListQuery());

        return $this->engine->renderResponse('Admin/Institution/list.html.twig',
            ['institutions' => $institutions]);
    }
}