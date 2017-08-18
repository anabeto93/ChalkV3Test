<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Normalizer;

use App\Domain\Model\User;
use Sonata\IntlBundle\Templating\Helper\LocaleHelper;

class UserNormalizer
{
    /** @var LocaleHelper */
    private $localeHelper;

    /**
     * @param LocaleHelper $localeHelper
     */
    public function __construct(LocaleHelper $localeHelper)
    {
        $this->localeHelper = $localeHelper;
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function normalize(User $user): array
    {
        return [
            'uuid'        => $user->getUuid(),
            'firstName'   => $user->getFirstName(),
            'lastName'    => $user->getLastName(),
            'phoneNumber' => $user->getPhoneNumber(),
            'countryCode' => $user->getCountry(),
            'country'     => $this->localeHelper->country($user->getCountry()),
            'locale'      => $user->getLocale(),
        ];
    }
}
