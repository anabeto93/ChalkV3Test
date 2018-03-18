<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 18/03/2018
 * Time: 18:11
 */

namespace App\Ui\Admin\Action\Cohort\CohortCourse;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Cohort\CohortCourse\CohortCourseListQuery;
use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param Institution $institution
     * @param Cohort $cohort
     * @return Response|RedirectResponse
     */
    public function __invoke(Request $request, Institution $institution, Cohort $cohort): Response {
        $courses = $this->queryBus->handle(new CohortCourseListQuery($cohort));

        return $this->engine->renderResponse('Admin/Cohort/CohortCourse/list.html.twig', [
            'institution' => $institution,
            'cohort' => $cohort,
            'courses' => $courses
        ]);
    }
}