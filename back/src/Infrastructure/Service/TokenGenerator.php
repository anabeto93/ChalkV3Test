<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Service;

class TokenGenerator
{

    /** @var array */
    private $allowedChars = [
        'a','z','e','r','t','y','u','i','o','p','q','s','d','f','g','h','j','k','m','w','x','v','b','n',
        'A','Z','E','R','T','Y','U','P','Q','S','D','F','G','H','J','K','L','M','W','X','C','V','B','N',
        2,3,4,5,6,7,8,9,
    ];

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateToken(int $length = 6): string
    {
        $token = '';

        for ($i = 0; $i  < $length; $i++) {
            $token .= $this->allowedChars[mt_rand(0, count($this->allowedChars) - 1)];
        }

        return $token;
    }
}
