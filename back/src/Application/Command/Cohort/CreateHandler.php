<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:50
 */

namespace App\Application\Command\Cohort;


use App\Domain\Exception\Cohort\TitleAlreadyUsedException;
use App\Domain\Model\Cohort;
use App\Domain\Repository\CohortRepositoryInterface;
use App\Domain\Uuid\Generator;

class CreateHandler {
    /** @var CohortRepositoryInterface */
    private $cohortRepository;

    /** @var Generator */
    private $uuidGenerator;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * CreateHandler constructor.
     * @param CohortRepositoryInterface $cohortRepository
     * @param Generator $uuidGenerator
     * @param \DateTimeInterface $dateTime
     */
    public function __construct(CohortRepositoryInterface $cohortRepository, Generator $uuidGenerator, \DateTimeInterface $dateTime) {
        $this->cohortRepository = $cohortRepository;
        $this->uuidGenerator = $uuidGenerator;
        $this->dateTime = $dateTime;
    }

    public function handle(Create $command) {
        $sameTitleCohort = $this->cohortRepository->findByInstitutionAndTitle
        ($command->institution, $command->title);

        if($sameTitleCohort !== null) {
            throw new TitleAlreadyUsedException();
        }

        $uuid = $this->uuidGenerator->generateUuid();

        $cohort = new Cohort(
            $uuid,
            $command->institution,
            $command->title,
            $this->dateTime
        );

        $this->cohortRepository->add($cohort);
    }
}