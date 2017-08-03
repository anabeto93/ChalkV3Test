<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\GraphQL\Resolver;

use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use Sonata\IntlBundle\Templating\Helper\LocaleHelper;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserResolver
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var LocaleHelper */
    private $localeHelper;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param LocaleHelper          $localeHelper
     */
    public function __construct(TokenStorageInterface $tokenStorage, LocaleHelper $localeHelper)
    {
        $this->tokenStorage = $tokenStorage;
        $this->localeHelper = $localeHelper;
    }

    /**
     * @return array
     *
     * @throw UserError
     */
    public function resolveUser(): array
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof ApiUserAdapter) {
            throw new UserError('Invalid parameter');
        }

        return [
            'uuid'        => $user->getUser()->getUuid(),
            'firstName'   => $user->getUser()->getFirstName(),
            'lastName'    => $user->getUser()->getLastName(),
            'phoneNumber' => $user->getUser()->getPhoneNumber(),
            'countryCode' => $user->getUser()->getCountry(),
            'country'     => $this->localeHelper->country($user->getUser()->getCountry()),
        ];
    }
}
