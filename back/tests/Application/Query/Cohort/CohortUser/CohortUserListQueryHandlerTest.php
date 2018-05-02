<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 09/04/2018
 * Time: 21:05
 */

namespace Tests\Application\Query\Cohort\CohortUser;


use App\Application\Query\Cohort\CohortUser\CohortUserListQuery;
use App\Application\Query\Cohort\CohortUser\CohortUserListQueryHandler;
use App\Application\View\User\UserListView;
use App\Application\View\User\UserView;
use App\Domain\Model\Cohort;
use App\Domain\Model\CohortUser;
use App\Domain\Model\User;
use App\Domain\Repository\CohortUserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CohortUserListQueryHandlerTest extends TestCase {
    public function testHandle() {
        // Context
        $cohortUserRepository = $this->prophesize(CohortUserRepositoryInterface::class);

        $cohort = $this->prophesize(Cohort::class);
        $cohortUser1 = $this->prophesize(CohortUser::class);
        $cohortUser2 = $this->prophesize(CohortUser::class);

        $cohortUsers = [$cohortUser1->reveal(), $cohortUser2->reveal()];

        $createdAt1 = new \DateTime('2018-04-04');
        $createdAt2 = new \DateTime('2018-04-05');
        $lastLoginAccessNotificationAt = new \DateTime('2018-04-07');

        $user1 = $this->prophesize(User::class);
        $user2 = $this->prophesize(User::class);


        $user1->getId()->willReturn(1);
        $user2->getId()->willReturn(2);
        $user1->getFirstName()->willReturn('FirstName1');
        $user1->getLastName()->willReturn('LastName1');
        $user1->getPhoneNumber()->willReturn('+123123123');
        $user1->getCountry()->willReturn('FR');
        $user1->getApiToken()->willReturn('token');
        $user1->getCreatedAt()->willReturn($createdAt1);
        $user1->getLastLoginAccessNotificationAt()->willReturn(null);
        $user1->isMultiLogin()->willReturn(false);

        $user2->getFirstName()->willReturn('FirstName2');
        $user2->getLastName()->willReturn('LastName2');
        $user2->getPhoneNumber()->willReturn('+321321321');
        $user2->getCountry()->willReturn('GH');
        $user2->getApiToken()->willReturn('token2');
        $user2->getCreatedAt()->willReturn($createdAt2);
        $user2->getLastLoginAccessNotificationAt()->willReturn($lastLoginAccessNotificationAt);
        $user2->isMultiLogin()->willReturn(true);

        // Mock
        $cohortUserRepository->findByCohort($cohort->reveal())
            ->shouldBeCalled()
            ->willReturn($cohortUsers);

        $cohortUser1->getUser()->shouldBeCalled()->willReturn($user1);
        $cohortUser2->getUser()->shouldBeCalled()->willReturn($user2);

        // handler
        $queryHandler = new CohortUserListQueryHandler(
            $cohortUserRepository->reveal()
        );

        $result = $queryHandler->handle(new CohortUserListQuery($cohort->reveal()));

        $expected = new UserListView(1, 1, 2);
        $expected->addUser(
            new UserView(
                1,
                'FirstName1',
                'LastName1',
                '+123123123',
                'FR',
                'token',
                $createdAt1,
                null,
                false
            )
        );
        $expected->addUser(
            new UserView(
                2,
                'FirstName2',
                'LastName2',
                '+321321321',
                'GH',
                'token2',
                $createdAt2,
                $lastLoginAccessNotificationAt,
                true
            )
        );

        $this->assertEquals($expected, $result);
    }
}