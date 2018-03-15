<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 16:07
 */

namespace App\Application\Command\Cohort;


use App\Domain\Repository\CohortRepositoryInterface;

class DeleteHandler {
    /** @var CohortRepositoryInterface */
    public $cohortRepository;

    /**
     * DeleteHandler constructor.
     * @param CohortRepositoryInterface $cohortRepository
     */
    public function __construct(CohortRepositoryInterface $cohortRepository) {
        $this->cohortRepository = $cohortRepository;
    }

    public function handle(Delete $command) {
        $this->cohortRepository->remove($command->cohort);
    }
}