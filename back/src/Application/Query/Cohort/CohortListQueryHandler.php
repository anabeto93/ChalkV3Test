<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:34
 */

namespace App\Application\Query\Cohort;


use App\Application\View\Cohort\CohortView;
use App\Domain\Repository\CohortCourseRepositoryInterface;
use App\Domain\Repository\CohortRepositoryInterface;
use App\Domain\Repository\CohortUserRepositoryInterface;

class CohortListQueryHandler {
    /** @var CohortRepositoryInterface */
    public $cohortRepository;

    /** @var CohortUserRepositoryInterface */
    public $cohortUserRepository;

    /** @var CohortCourseRepositoryInterface */
    public $cohortCourseRepository;

    /**
     * CohortListQueryHandler constructor.
     * @param CohortRepositoryInterface $cohortRepository
     * @param CohortUserRepositoryInterface $cohortUserRepository
     * @param CohortCourseRepositoryInterface $cohortCourseRepository
     */
    public function __construct(CohortRepositoryInterface $cohortRepository, CohortUserRepositoryInterface $cohortUserRepository, CohortCourseRepositoryInterface $cohortCourseRepository) {
        $this->cohortRepository = $cohortRepository;
        $this->cohortUserRepository = $cohortUserRepository;
        $this->cohortCourseRepository = $cohortCourseRepository;
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
                $this->cohortUserRepository->counterUserForCohort($cohort),
                $this->cohortCourseRepository->countCourseForCohort($cohort)
            );
        }

        return $cohortViews;
    }
}