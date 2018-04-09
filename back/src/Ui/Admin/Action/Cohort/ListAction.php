<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 14:50
 */

namespace App\Ui\Admin\Action\Cohort;


use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Cohort\CohortListQuery;
use App\Domain\Model\Institution;
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
     * @param Institution $institution
     * @return Response
     */
    public function __invoke(Institution $institution): Response {
        $cohorts = $this->queryBus->handle(new CohortListQuery($institution));

        return $this->engine->renderResponse('Admin/Cohort/list.html.twig', [
            'cohorts' => $cohorts,
            'institution' => $institution
        ]);
    }
}