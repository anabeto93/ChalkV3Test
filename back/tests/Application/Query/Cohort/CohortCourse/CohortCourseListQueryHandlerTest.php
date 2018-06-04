<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 09/04/2018
 * Time: 19:24
 */

namespace Tests\Application\Query\Cohort\CohortCourse;


use App\Application\Query\Cohort\CohortCourse\CohortCourseListQuery;
use App\Application\Query\Cohort\CohortCourse\CohortCourseListQueryHandler;
use App\Application\View\Course\CourseView;
use App\Domain\Model\Cohort;
use App\Domain\Model\CohortCourse;
use App\Domain\Model\Course;
use App\Domain\Repository\CohortCourseRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CohortCourseListQueryHandlerTest extends TestCase {
    public function testHandle() {
        //Context
        $cohortCourseRepository = $this->prophesize(CohortCourseRepositoryInterface::class);

        $cohort = $this->prophesize(Cohort::class);
        $cohortCourse1 = $this->prophesize(CohortCourse::class);
        $cohortCourse2 = $this->prophesize(CohortCourse::class);
        $course1 = $this->prophesize(Course::class);
        $course2 = $this->prophesize(Course::class);

        $cohortCourses = [$cohortCourse1->reveal(), $cohortCourse2->reveal()];

        $course1->getId()->shouldBeCalled()->willReturn(1);
        $course1->getTitle()->shouldBeCalled()->willReturn('title 1');
        $course1->getTeacherName()->shouldBeCalled()->willReturn('teacher 1');
        $course1->getFolders()->shouldBeCalled()->willReturn([]);
        $course1->getSessions()->shouldBeCalled()->willReturn([]);
        $course1->getUsers()->shouldBeCalled()->willReturn([]);
        $course1->isEnabled()->shouldBeCalled()->willReturn(true);

        $course2->getId()->shouldBeCalled()->willReturn(2);
        $course2->getTitle()->shouldBeCalled()->willReturn('title 2');
        $course2->getTeacherName()->shouldBeCalled()->willReturn('teacher 2');
        $course2->getFolders()->shouldBeCalled()->willReturn([]);
        $course2->getSessions()->shouldBeCalled()->willReturn([]);
        $course2->getUsers()->shouldBeCalled()->willReturn([]);
        $course2->isEnabled()->shouldBeCalled()->willReturn(false);

        //Mock
        $cohortCourseRepository->findByCohort($cohort->reveal())
                            ->shouldBeCalled()
                            ->willReturn($cohortCourses);

        $cohortCourse1->getCourse()->shouldBeCalled()->willReturn($course1);
        $cohortCourse2->getCourse()->shouldBeCalled()->willReturn($course2);

        //Handler
        $queryHandler = new CohortCourseListQueryHandler(
            $cohortCourseRepository->reveal()
        );

        $result = $queryHandler->handle(new CohortCourseListQuery($cohort->reveal()));

        //Expected
        $expected = [
            new CourseView(1, 'title 1', 'teacher 1', true, 0, 0, 0),
            new CourseView(2, 'title 2', 'teacher 2', false, 0, 0, 0)
        ];

        $this->assertEquals($expected, $result);
    }
}