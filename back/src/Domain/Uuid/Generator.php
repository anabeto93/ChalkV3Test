<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Uuid;

class Generator
{
    /**
     * @return string
     */
    public function generateUuid(): string
    {
        return sprintf('%s-%s-%s',
            substr($this->generateUniqId(), 0, 10),
            substr($this->generateUniqId(), 0, 5),
            substr($this->generateUniqId(), 0, 5)
        );
    }

    /**
     * @return string
     */
    private function generateUniqId(): string
    {
        return hash('sha256', sprintf('%s%s', $this->getRandomUniqId(), $this->getRandomUniqId()));
    }

    /**
     * @return string
     */
    public function getRandomUniqId(): string
    {
        return uniqid(mt_rand());
    }
}
