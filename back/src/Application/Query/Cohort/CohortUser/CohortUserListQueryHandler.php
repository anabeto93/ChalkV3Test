<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 17:05
 */

namespace App\Application\Query\Cohort\CohortUser;


use App\Application\View\User\UserListView;
use App\Application\View\User\UserView;
use App\Domain\Repository\CohortUserRepositoryInterface;

class CohortUserListQueryHandler {
    const USERS_DEFAULT_PAGE = 1;
    const USERS_NUMBER_OF_PAGES = 1;

    /** @var CohortUserRepositoryInterface */
    private $cohortUserRepository;

    /**
     * CohortUserListQueryHandler constructor.
     * @param CohortUserRepositoryInterface $cohortUserRepository
     */
    public function __construct(CohortUserRepositoryInterface $cohortUserRepository) {
        $this->cohortUserRepository = $cohortUserRepository;
    }

    /**
     * @param CohortUserListQuery $query
     * @return UserListView
     */
    public function handle(CohortUserListQuery $query): UserListView {
        $cohortUsers = $this->cohortUserRepository->findByCohort($query->cohort);

        $userListView = new UserListView(self::USERS_DEFAULT_PAGE, self::USERS_NUMBER_OF_PAGES,
            count($cohortUsers));

        foreach ($cohortUsers as $cohortUser) {
            $user = $cohortUser->getUser();

            $userListView->addUser(new UserView(
                $user->getId(),
                $user->getFirstName(),
                $user->getLastName(),
                $user->getPhoneNumber(),
                $user->getCountry(),
                $user->getApiToken(),
                $user->getCreatedAt(),
                $user->getLastLoginAccessNotificationAt(),
                $user->isMultiLogin()
            ));
        }

        return $userListView;
    }
}