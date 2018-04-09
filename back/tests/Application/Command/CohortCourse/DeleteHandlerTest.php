<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 09/04/2018
 * Time: 21:31
 */

namespace Tests\Application\Command\CohortCourse;


use App\Application\Command\CohortCourse\Delete;
use App\Application\Command\CohortCourse\DeleteHandler;
use App\Domain\Model\Cohort;
use App\Domain\Model\CohortCourse;
use App\Domain\Model\Course;
use App\Domain\Model\User;
use App\Domain\Repository\CohortCourseRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class DeleteHandlerTest extends TestCase {
    /** @var ObjectProphecy */
    private $cohortCourseRepository;

    protected function setUp() {
        $this->cohortCourseRepository = $this->prophesize(CohortCourseRepositoryInterface::class);
    }

    public function testHandle() {
        //Context
        $cohortCourse = $this->prophesize(CohortCourse::class);
        $course = $this->prophesize(Course::class);
        $cohort = $this->prophesize(Cohort::class);

        //Mock
        $cohortCourse->getCourse()->shouldBeCalled()->willReturn($course->reveal());
        $cohortCourse->getCohort()->shouldBeCalled()->willReturn($cohort->reveal());
        $cohort->getUsers()->shouldBeCalled()->willReturn([]);

        $this->cohortCourseRepository->remove($cohortCourse->reveal())->shouldBeCalled();

        //Handler
        $handler = new DeleteHandler($this->cohortCourseRepository->reveal());
        $handler->handle(new Delete($cohortCourse->reveal()));
    }

    public function testHandleWithUsers() {
        //Context
        $cohortCourse = $this->prophesize(CohortCourse::class);
        $course = $this->prophesize(Course::class);
        $cohort = $this->prophesize(Cohort::class);
        $user1 = $this->prophesize(User::class);
        $user2 = $this->prophesize(User::class);

        //Mock
        $cohortCourse->getCourse()->shouldBeCalled()->willReturn($course->reveal());
        $cohortCourse->getCohort()->shouldBeCalled()->willReturn($cohort->reveal());
        $cohort->getUsers()->shouldBeCalled()->willReturn([$user1->reveal()]);
        $course->getUsers()->shouldBeCalled()->willReturn([$user1->reveal(), $user2->reveal()]);
        $course->removeUserCourse($user1->reveal(), $course->reveal())->shouldBeCalled();
        $user1->forceUpdate()->shouldBeCalled();

        $this->cohortCourseRepository->remove($cohortCourse->reveal())->shouldBeCalled();

        //Handler
        $handler = new DeleteHandler($this->cohortCourseRepository->reveal());
        $handler->handle(new Delete($cohortCourse->reveal()));
    }
}