<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 14:41
 */

namespace App\Application\Command\Institution;


use App\Domain\Repository\InstitutionRepositoryInterface;

class DeleteHandler {
    /** @var InstitutionRepositoryInterface */
    private $institutionRepository;

    /**
     * DeleteHandler constructor.
     * @param InstitutionRepositoryInterface $institutionRepository
     */
    public function __construct(InstitutionRepositoryInterface $institutionRepository) {
        $this->institutionRepository = $institutionRepository;
    }

    /**
     * @param Delete $command
     */
    public function handle(Delete $command) {
        $this->institutionRepository->remove($command->institution);
    }
}