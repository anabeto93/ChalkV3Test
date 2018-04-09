<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 04/04/2018
 * Time: 14:52
 */

namespace Tests\Application\Query\Institution;


use App\Application\Query\Institution\InstitutionListQuery;
use App\Application\Query\Institution\InstitutionListQueryHandler;
use App\Application\View\Institution\InstitutionView;
use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;
use App\Domain\Repository\InstitutionRepositoryInterface;
use PHPUnit\Framework\TestCase;

class InstitutionListQueryHandlerTest extends TestCase {
    public function testHandle() {
        //Context
        $institution1 = $this->prophesize(Institution::class);
        $institution2 = $this->prophesize(Institution::class);
        $institution3 = $this->prophesize(Institution::class);

        $cohort1 = $this->prophesize(Cohort::class);
        $cohort2 = $this->prophesize(Cohort::class);
        $cohort3 = $this->prophesize(Cohort::class);

        $institution1->getId()->shouldBeCalled()->willReturn(1);
        $institution2->getId()->shouldBeCalled()->willReturn(2);
        $institution3->getId()->shouldBeCalled()->willReturn(3);

        $institution1->getName()->shouldBeCalled()->willReturn('name 1');
        $institution2->getName()->shouldBeCalled()->willReturn('name 2');
        $institution3->getName()->shouldBeCalled()->willReturn('name 3');

        $institution1->getCohorts()->shouldBeCalled()->willReturn([$cohort1->reveal()]);
        $institution2->getCohorts()->shouldBeCalled()->willReturn([]);
        $institution3->getCohorts()->shouldBeCalled()->willReturn([$cohort2->reveal(),
            $cohort3->reveal()]);


        //Mock
        $institutionRepository = $this->prophesize(InstitutionRepositoryInterface::class);
        $institutionRepository->getAll()->shouldBeCalled()->willReturn([
            $institution1->reveal(),
            $institution2->reveal(),
            $institution3->reveal()
        ]);

        $handler = new InstitutionListQueryHandler($institutionRepository->reveal());
        $result = $handler->handle(new InstitutionListQuery());

        $expected = [
            new InstitutionView(1, 'name 1', 1),
            new InstitutionView(2, 'name 2', 0),
            new InstitutionView(3, 'name 3', 2)
        ];

        $this->assertEquals($expected, $result);
    }
}