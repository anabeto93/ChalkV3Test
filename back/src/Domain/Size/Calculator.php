<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Size;

class Calculator
{
    /**
     * @param string $string
     *
     * @return int
     */
    public function calculateSize(string $string): int
    {
        return mb_strlen($string, '8bit');
    }
}
