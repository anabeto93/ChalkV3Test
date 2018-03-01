<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 01:08
 */

namespace App\Application\Command\Institution;


use App\Domain\Repository\InstitutionRepositoryInterface;

class UpdateHandler {
    /** @var InstitutionRepositoryInterface */
    private $institutionRepository;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * UpdateHandler constructor.
     * @param InstitutionRepositoryInterface $institutionRepository
     * @param \DateTimeInterface $dateTime
     */
    public function __construct(InstitutionRepositoryInterface $institutionRepository, \DateTimeInterface $dateTime) {
        $this->institutionRepository = $institutionRepository;
        $this->dateTime = $dateTime;
    }

    public function handle(Update $command) {
        $command->institution->update(
            $command->institution->getUuid(),
            $command->name,
            $this->dateTime,
            0
        );

        $this->institutionRepository->set($command->institution);
    }
}