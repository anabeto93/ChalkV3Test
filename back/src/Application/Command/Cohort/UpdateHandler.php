<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 15:39
 */

namespace App\Application\Command\Cohort;


use App\Domain\Exception\Cohort\TitleAlreadyUsedException;
use App\Domain\Repository\CohortRepositoryInterface;

class UpdateHandler {
    /** @var CohortRepositoryInterface */
    private $cohortRepository;

    /** @var \DateTimeInterface */
    private $datetime;

    /**
     * UpdateHandler constructor.
     * @param CohortRepositoryInterface $cohortRepository
     * @param \DateTimeInterface $datetime
     */
    public function __construct(CohortRepositoryInterface $cohortRepository, \DateTimeInterface $datetime) {
        $this->cohortRepository = $cohortRepository;
        $this->datetime = $datetime;
    }

    /**
     * @param Update $command
     */
    public function handle(Update $command) {
        $sameTitleCohort = $this->cohortRepository->findByInstitutionAndTitle
        ($command->institution, $command->title);

        if($sameTitleCohort !== null) {
            throw new TitleAlreadyUsedException();
        }

        $command->cohort->update(
          $command->title,
          $this->datetime
        );

        $this->cohortRepository->set($command->cohort);
    }
}