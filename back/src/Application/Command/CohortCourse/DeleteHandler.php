<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 19/03/2018
 * Time: 01:31
 */

namespace App\Application\Command\CohortCourse;


use App\Domain\Repository\CohortCourseRepositoryInterface;

class DeleteHandler {
    /** @var CohortCourseRepositoryInterface */
    private $cohortCourseRepository;

    /**
     * DeleteHandler constructor.
     * @param CohortCourseRepositoryInterface $cohortCourseRepository
     */
    public function __construct(CohortCourseRepositoryInterface $cohortCourseRepository) {
        $this->cohortCourseRepository = $cohortCourseRepository;
    }

    /**
     * @param Delete $command
     */
    public function handle(Delete $command) {
        $this->cohortCourseRepository->remove($command->cohortCourse);
    }
}