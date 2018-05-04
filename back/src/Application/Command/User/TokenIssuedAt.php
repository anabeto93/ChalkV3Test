<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 26/04/2018
 * Time: 03:16
 */

namespace App\Application\Command\User;


use App\Application\Command\Command;
use App\Domain\Model\User;

class TokenIssuedAt implements Command {
    /** @var User */
    public $user;

    /** @var \DateTimeInterface|null  */
    public $apiTokenIssuedAt;

    /**
     * TokenIssuedAt constructor.
     * @param User $user
     */
    public function __construct(User $user) {
        $this->user = $user;
        $this->apiTokenIssuedAt = $user->getApiTokenIssuedAt();
    }
}