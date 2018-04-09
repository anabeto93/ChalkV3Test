<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 09/04/2018
 * Time: 21:31
 */

namespace Tests\Application\Command\CohortUser;


use App\Application\Command\CohortUser\Delete;
use App\Application\Command\CohortUser\DeleteHandler;
use App\Domain\Model\Cohort;
use App\Domain\Model\CohortUser;
use App\Domain\Model\User;
use App\Domain\Model\Course;
use App\Domain\Repository\CohortUserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class DeleteHandlerTest extends TestCase {
    /** @var ObjectProphecy */
    private $cohortUserRepository;

    protected function setUp() {
        $this->cohortUserRepository = $this->prophesize(CohortUserRepositoryInterface::class);
    }

    public function testHandle() {
        //Context
        $cohortUser = $this->prophesize(CohortUser::class);
        $user = $this->prophesize(User::class);
        $cohort = $this->prophesize(Cohort::class);

        //Mock
        $cohortUser->getUser()->shouldBeCalled()->willReturn($user->reveal());
        $cohortUser->getCohort()->shouldBeCalled()->willReturn($cohort->reveal());
        $cohort->getCourses()->shouldBeCalled()->willReturn([]);

        $this->cohortUserRepository->remove($cohortUser->reveal())->shouldBeCalled();

        //Handler
        $handler = new DeleteHandler($this->cohortUserRepository->reveal());
        $handler->handle(new Delete($cohortUser->reveal()));
    }

    public function testHandleWithCourses() {
        //Context
        $cohortUser = $this->prophesize(CohortUser::class);
        $user = $this->prophesize(User::class);
        $cohort = $this->prophesize(Cohort::class);
        $course1 = $this->prophesize(Course::class);
        $course2 = $this->prophesize(Course::class);

        //Mock
        $cohortUser->getUser()->shouldBeCalled()->willReturn($user->reveal());
        $cohortUser->getCohort()->shouldBeCalled()->willReturn($cohort->reveal());
        $cohort->getCourses()->shouldBeCalled()->willReturn([$course1->reveal(), $course2->reveal()]);

        $course1->getUsers()->shouldBeCalled()->willReturn([$user]);
        $course1->removeUserCourse($user->reveal(), $course1->reveal())->shouldBeCalled();

        $course2->getUsers()->shouldBeCalled()->willReturn([]);

        $user->forceUpdate()->shouldBeCalled();

        $this->cohortUserRepository->remove($cohortUser->reveal())->shouldBeCalled();

        //Handler
        $handler = new DeleteHandler($this->cohortUserRepository->reveal());
        $handler->handle(new Delete($cohortUser->reveal()));
    }
}