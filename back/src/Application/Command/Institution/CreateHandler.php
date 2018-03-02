<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 00:53
 */

namespace App\Application\Command\Institution;


use App\Domain\Exception\Institution\NameAlreadyUsedException;
use App\Domain\Model\Institution;
use App\Domain\Repository\InstitutionRepositoryInterface;
use App\Domain\Uuid\Generator;

class CreateHandler {
    /** @var InstitutionRepositoryInterface */
    private $institutionRepository;

    /** @var Generator */
    private $generator;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * CreateHandler constructor.
     * @param InstitutionRepositoryInterface $institutionRepository
     * @param Generator $generator
     * @param \DateTimeInterface $dateTime
     */
    public function __construct(InstitutionRepositoryInterface $institutionRepository, Generator $generator, \DateTimeInterface $dateTime) {
        $this->institutionRepository = $institutionRepository;
        $this->generator = $generator;
        $this->dateTime = $dateTime;
    }

    /**
     * @param Create $command
     * @throws NameAlreadyUsedException
     */
    public function handle(Create $command) {
        $institutionWithSameName = $this->institutionRepository->findByName($command->name);

        if($institutionWithSameName) {
            throw new NameAlreadyUsedException();
        }

        $uuid = $this->generator->generateUuid();

        $institution = new Institution(
            $uuid,
            $command->name,
            $this->dateTime
        );

        $this->institutionRepository->add($institution);
    }
}