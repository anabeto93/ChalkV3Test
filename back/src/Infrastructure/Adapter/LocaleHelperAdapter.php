<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Adapter;

use App\Application\Adapter\LocaleHelperInterface;
use Sonata\IntlBundle\Templating\Helper\LocaleHelper;

class LocaleHelperAdapter implements LocaleHelperInterface
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
     * @param string|null $country
     *
     * @return bool
     */
    public function isCountryValid(string $country = null): bool
    {
        return '' !== $this->localeHelper->country($country);
    }
}
