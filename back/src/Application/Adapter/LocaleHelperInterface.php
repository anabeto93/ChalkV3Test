<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Adapter;

interface LocaleHelperInterface
{
    /**
     * @param string|null $country
     *
     * @return bool
     */
    public function isCountryValid(string $country = null): bool;
}
