<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:34
 */

namespace App\Application\Query\Cohort;


use App\Application\View\Cohort\CohortView;
use App\Domain\Repository\CohortRepositoryInterface;
use App\Domain\Repository\CohortUserRepositoryInterface;

class CohortListQueryHandler {
    /** @var CohortRepositoryInterface */
    public $cohortRepository;

    /** @var CohortUserRepositoryInterface */
    public $cohortUserRepository;

    /**
     * CohortListQueryHandler constructor.
     * @param CohortRepositoryInterface $cohortRepository
     * @param CohortUserRepositoryInterface $cohortUserRepository
     */
    public function __construct(CohortRepositoryInterface $cohortRepository, CohortUserRepositoryInterface $cohortUserRepository) {
        $this->cohortRepository = $cohortRepository;
        $this->cohortUserRepository = $cohortUserRepository;
    }

    /**
     * @param CohortListQuery $query
     * @return array
     */
    public function handle(CohortListQuery $query): array {
        $cohortViews = [];
        $cohorts = $this->cohortRepository->findByInstitution($query->institution);

        foreach($cohorts as $cohort) {
            $cohortViews[] = new CohortView(
                $cohort->getId(),
                $cohort->getTitle(),
                $this->cohortUserRepository->counterUserForCohort($cohort)
            );
        }

        return $cohortViews;
    }
}