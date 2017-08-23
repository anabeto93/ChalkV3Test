<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\GraphQL\Resolver;

class CurrentDateResolver
{
    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * @param \DateTimeInterface $dateTime
     */
    public function __construct(\DateTimeInterface $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return \DateTimeInterface
     */
    public function resolveCurrentDate(): \DateTimeInterface
    {
        return $this->dateTime;
    }
}
