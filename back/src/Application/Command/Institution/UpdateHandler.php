<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 01:08
 */

namespace App\Application\Command\Institution;


use App\Domain\Exception\Institution\NameAlreadyUsedException;
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

    /**
     * @param Update $command
     * @throws NameAlreadyUsedException
     */
    public function handle(Update $command) {
        if($command->institution->getName() !== $command->name) {
            $institutionWithSameName = $this->institutionRepository->findByName($command->name);

            if ($institutionWithSameName) {
                throw new NameAlreadyUsedException();
            }
        }

        $command->institution->update(
            $command->name,
            $this->dateTime,
            0
        );

        $this->institutionRepository->set($command->institution);
    }
}