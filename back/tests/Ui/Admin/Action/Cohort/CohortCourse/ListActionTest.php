<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 09/04/2018
 * Time: 14:27
 */

namespace Tests\Ui\Admin\Action\Cohort\CohortCourse;


use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Cohort\CohortCourse\CohortCourseListQuery;
use App\Application\View\Course\CourseView;
use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;
use App\Ui\Admin\Action\Cohort\CohortCourse\ListAction;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ListActionTest extends TestCase {
    public function testInvoke() {
        //Context
        $institution = $this->prophesize(Institution::class);
        $cohort = $this->prophesize(Cohort::class);
        $course1 = $this->prophesize(CourseView::class);
        $course2 = $this->prophesize(CourseView::class);

        $courses = [$course1->reveal(), $course2->reveal()];

        //Mock
        $engine = $this->prophesize(EngineInterface::class);
        $queryBus = $this->prophesize(QueryBusInterface::class);
        $queryBus->handle(new CohortCourseListQuery($cohort->reveal()))
            ->shouldBeCalled()
            ->willReturn($courses);

        $response = new Response();
        $engine->renderResponse("Admin/Cohort/CohortCourse/list.html.twig", [
            'institution' => $institution->reveal(),
            'cohort' => $cohort->reveal(),
            'courses' => $courses
        ])->shouldBeCalled()->willReturn($response);

        $action = new ListAction($engine->reveal(), $queryBus->reveal());
        $result = $action($institution->reveal(), $cohort->reveal());

        $this->assertInstanceOf(Response::class, $result);
    }
}
