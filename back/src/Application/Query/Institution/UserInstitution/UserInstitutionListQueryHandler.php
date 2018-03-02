<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 02/03/2018
 * Time: 17:03
 */

namespace App\Application\Query\Institution\UserInstitution;


use App\Application\View\User\UserListView;
use App\Application\View\User\UserView;
use App\Domain\Repository\UserInstitutionRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class UserInstitutionListQueryHandler {
    const USERS_DEFAULT_PAGE = 1;
    const USERS_NUMBER_OF_PAGES = 1;

    /** @var UserInstitutionRepositoryInterface */
    private $userInstitutionRepository;

    /**
     * UserInstitutionListQueryHandler constructor.
     * @param UserInstitutionRepositoryInterface $userInstitutionRepository
     */
    public function __construct(UserInstitutionRepositoryInterface $userInstitutionRepository, UserRepositoryInterface $userRepository) {
        $this->userInstitutionRepository = $userInstitutionRepository;
    }


    /**
     * @param UserInstitutionListQuery $userInstitutionListQuery
     * @return UserListView
     */
    public function handle(UserInstitutionListQuery $userInstitutionListQuery): UserListView {
        $userInstitutions = $this->userInstitutionRepository->findByInstitution
        ($userInstitutionListQuery->institution);

        $userListView = new UserListView(self::USERS_DEFAULT_PAGE, self::USERS_NUMBER_OF_PAGES,
            sizeof($userInstitutions));

        foreach($userInstitutions as $userInstitution) {
            $user = $userInstitution->getUser();

            $userListView->addUser(new UserView(
                $user->getId(),
                $user->getFirstName(),
                $user->getLastName(),
                $user->getPhoneNumber(),
                $user->getCountry(),
                $user->getApiToken(),
                $user->getCreatedAt(),
                $user->getLastLoginAccessNotificationAt()
            ));
        }

        return $userListView;
    }
}