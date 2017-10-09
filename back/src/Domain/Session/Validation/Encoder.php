<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Session\Validation;

class Encoder
{
    /**
     * @param string $privateKey
     * @param string $userUuid
     * @param string $sessionUuid
     *
     * @return string
     */
    public function getUnlockCodeForSession(string $privateKey, string $userUuid, string $sessionUuid): string
    {
        return $this->getCodeFromUuid($privateKey, $userUuid) . $this->getCodeFromUuid($privateKey, $sessionUuid);
    }

    /**
     * @param string $privateKey
     * @param string $uuid
     *
     * @return string
     */
    public function getCodeFromUuid(string $privateKey, string $uuid): string
    {
        $remainingLength = mb_strlen($privateKey) - mb_strlen(Constant::HEXADECIMAL_STRING) - 1;
        $privateKeyToArray = str_split($privateKey);
        $firstCharPosition = mt_rand(0, $remainingLength);
        $firstChar = $privateKeyToArray[$firstCharPosition];

        $chars = str_replace('-', '', $uuid);
        $charsToArray = str_split($chars);

        $result = $firstChar;

        foreach ($charsToArray as $char) {
            $positionOfChar = $firstCharPosition + mb_strpos(Constant::HEXADECIMAL_STRING, $char) + 1;
            $result .= $privateKeyToArray[$positionOfChar];
        }

        return $result;
    }
}
