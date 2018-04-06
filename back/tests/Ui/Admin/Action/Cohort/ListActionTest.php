<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 06/04/2018
 * Time: 17:12
 */

namespace Tests\Ui\Admin\Action\Cohort;


use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Cohort\CohortListQuery;
use App\Application\View\Cohort\CohortView;
use App\Domain\Model\Institution;
use App\Ui\Admin\Action\Cohort\ListAction;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ListActionTest extends TestCase {
    public function testInvoke() {
        //Context
        $institution = $this->prophesize(Institution::class);
        $cohort1 = $this->prophesize(CohortView::class);
        $cohort2 = $this->prophesize(CohortView::class);

        $cohorts = [$cohort1->reveal(), $cohort2->reveal()];

        //Mock
        $engine = $this->prophesize(EngineInterface::class);
        $queryBus = $this->prophesize(QueryBusInterface::class);
        $queryBus->handle(new CohortListQuery($institution->reveal()))
                ->shouldBeCalled()
                ->willReturn($cohorts);

        $response = new Response();
        $engine->renderResponse("Admin/Cohort/list.html.twig", [
            'institution' => $institution->reveal(),
            'cohorts' => $cohorts
        ])->shouldBeCalled()->willReturn($response);

        $action = new ListAction($engine->reveal(), $queryBus->reveal());
        $result = $action($institution->reveal());

        $this->assertInstanceOf(Response::class, $result);
    }
}