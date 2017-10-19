<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Session\Validation;

class Decoder
{
    /**
     * @param string $privateKey
     * @param string $code
     *
     * @return string
     */
    public function getUuidFromCode(string $privateKey, string $code): string
    {
        $chars             = trim($code);
        $codeHashedToArray = str_split($chars);
        $firstChar         = array_shift($codeHashedToArray);
        $begin             = mb_strpos($privateKey, $firstChar);

        $result = '';

        foreach ($codeHashedToArray as $codeChar) {
            $positionOfChar = mb_strpos($privateKey, $codeChar) - $begin - 1;
            $result .= mb_substr(Constant::HEXADECIMAL_STRING, $positionOfChar, 1);
        }

        return sprintf('%s-%s-%s', mb_substr($result, 0, 10),  mb_substr($result, 10, 5), mb_substr($result, 15, 5));
    }

    /**
     * @param string $privateKey
     * @param string $code
     *
     * @return string
     */
    public function getSessionUuidFromCode(string $privateKey, string $code): string
    {
        return $this->getUuidFromCode($privateKey, mb_substr($code, mb_strlen($code) / 2, mb_strlen($code)));
    }

    /**
     * @param string $privateKey
     * @param string $code
     *
     * @return string
     */
    public function getUserUuidFromCode(string $privateKey, string $code): string
    {
        return $this->getUuidFromCode($privateKey, mb_substr($code, 0, mb_strlen($code) / 2));
    }
}
