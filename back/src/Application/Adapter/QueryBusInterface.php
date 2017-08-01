<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Adapter;

use App\Application\Query\Query;

interface QueryBusInterface
{
    /**
     * @param Query $query
     *
     * @return mixed
     */
    public function handle(Query $query);
}
