<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 05/04/2018
 * Time: 23:24
 */

namespace Tests\Application\Query\Cohort;


use App\Application\Query\Cohort\CohortListQuery;
use App\Application\Query\Cohort\CohortListQueryHandler;
use App\Application\View\Cohort\CohortView;
use App\Domain\Model\Cohort;
use App\Domain\Model\Course;
use App\Domain\Model\Institution;
use App\Domain\Model\User;
use App\Domain\Repository\CohortCourseRepositoryInterface;
use App\Domain\Repository\CohortRepositoryInterface;
use App\Domain\Repository\CohortUserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CohortListQueryHandlerTest extends TestCase {
    public function testHandle() {
        //Context
        $cohortRepository = $this->prophesize(CohortRepositoryInterface::class);
        $cohortUserRepository = $this->prophesize(CohortUserRepositoryInterface::class);
        $cohortCourseRepository = $this->prophesize(CohortCourseRepositoryInterface::class);

        $institution = $this->prophesize(Institution::class);
        $cohort1 = $this->prophesize(Cohort::class);
        $cohort2 = $this->prophesize(Cohort::class);
        $user = $this->prophesize(User::class);
        $course = $this->prophesize(Course::class);

        $cohort1->getId()->willReturn(1);
        $cohort2->getId()->willReturn(2);
        $cohort1->getTitle()->willReturn('title 1');
        $cohort2->getTitle()->willReturn('title 2');

        $cohortUserRepository->countUserForCohort($cohort1)->willReturn(count([$user->reveal()]));
        $cohortCourseRepository->countCourseForCohort($cohort2)->willReturn(count([$course->reveal()
        ]));

        //Mock
        $cohortRepository->findByInstitution($institution->reveal())
                        ->shouldBeCalled()
                        ->willReturn([$cohort1->reveal(), $cohort2->reveal()]);
        $cohortUserRepository->countUserForCohort($cohort1)->shouldBeCalled()->willReturn(1);
        $cohortCourseRepository->countCourseForCohort($cohort1)->shouldBeCalled()->willReturn(0);

        $cohortUserRepository->countUserForCohort($cohort2)->shouldBeCalled()->willReturn(0);
        $cohortCourseRepository->countCourseForCohort($cohort2)->shouldBeCalled()->willReturn(1);

        //Handler
        $queryHandler = new CohortListQueryHandler(
            $cohortRepository->reveal(),
            $cohortUserRepository->reveal(),
            $cohortCourseRepository->reveal()
        );
        $result = $queryHandler->handle(new CohortListQuery($institution->reveal()));

        //Expected
        $expected = [
            new CohortView(1, 'title 1', 1, 0),
            new CohortView(2, 'title 2', 0, 1)
        ];

        $this->assertEquals($expected, $result);
    }
}