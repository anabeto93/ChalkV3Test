<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 26/04/2018
 * Time: 03:21
 */

namespace App\Application\Command\User;


use App\Domain\Repository\UserRepositoryInterface;

class TokenIssuedAtHandler {
    /** @var UserRepositoryInterface */
    private $userRepository;

    /**
     * TokenIssuedAtHandler constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param TokenIssuedAt $command
     */
    public function handle(TokenIssuedAt $command) {
        $command->user->setApiTokenIssuedAt($command->apiTokenIssuedAt);
        $this->userRepository->set($command->user);
    }
}