<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 05/04/2018
 * Time: 11:31
 */

namespace Tests\Ui\Admin\Action\Institution;


use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Institution\InstitutionListQuery;
use App\Application\View\Institution\InstitutionView;
use App\Ui\Admin\Action\Institution\ListAction;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ListActionTest extends TestCase {
    public function testInvoke() {
        //Context
        $institution1 = new InstitutionView(1, 'name1', 1, 0);
        $institution2 = new InstitutionView(2, 'name2', 2, 1);

        //Mock
        $engine = $this->prophesize(EngineInterface::class);
        $queryBus = $this->prophesize(QueryBusInterface::class);

        $queryBus->handle(new InstitutionListQuery())->shouldBeCalled()->willReturn
        ([$institution1, $institution2]);

        $response = new Response();
        $engine->renderResponse("Admin/Institution/list.html.twig", ['institutions' =>
            [$institution1, $institution2]])
            ->shouldBeCalled()
            ->willReturn($response);

        $action = new ListAction($engine->reveal(), $queryBus->reveal());
        $result = $action();

        $this->assertInstanceOf(Response::class, $result);
    }
}