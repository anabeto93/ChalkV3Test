<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Domain\Session\Validation;

use App\Domain\Session\Validation\Decoder;
use App\Domain\Session\Validation\Encoder;
use PHPUnit\Framework\TestCase;

class EncoderDecoderTest extends TestCase
{
    /**
     * @dataProvider dataSet
     *
     * @param string $uuid
     * @param string $privateKey
     */
    public function testEncoderDecoder(string $uuid, string $privateKey)
    {
        $encoder = new Encoder();
        $decoder = new Decoder();

        $code = $encoder->getCodeFromUuid($privateKey, $uuid);
        $result = $decoder->getUuidFromCode($privateKey, $code);

        $this->assertEquals($uuid, $result);
    }

    public function testDecode()
    {
        $userUuid    = 'd1fae8c298-adb2e-e878b';
        $userCode    = "dmGChA6keq6hmQeAA6g6Q";
        $sessionUuid = 'a6342f0bf7-a2aae-5a485';
        $sessionCode = 'dhWK9eCZQCghehhAfh96f';
        $privateKey  = "i5Rj10bMdZGeK9fWg6qhQkmACn8YrPpBstuv2DwNXVxayz7EFUcH3JLS4T";
        $fullCode    = '2cw3EHzUN7zEcFNHHzyzFBxwv2uEsaENxuxx7Dx2XD';

        $decoder                       = new Decoder();
        $userUuidDecode                = $decoder->getUuidFromCode($privateKey, $userCode);
        $sessionUuidDecode             = $decoder->getUuidFromCode($privateKey, $sessionCode);
        $userUuidDecodeFromFullCode    = $decoder->getUserUuidFromCode($privateKey, $fullCode);
        $sessionUuidDecodeFromFullCode = $decoder->getSessionUuidFromCode($privateKey, $fullCode);

        $this->assertEquals($userUuid, $userUuidDecode);
        $this->assertEquals($sessionUuid, $sessionUuidDecode);
        $this->assertEquals($sessionUuid, $sessionUuidDecodeFromFullCode);
        $this->assertEquals($userUuid, $userUuidDecodeFromFullCode);
    }

    public function dataSet()
    {
        return [
            [
                '4bf73791a3-18a2c-4340d',
                "i5Rj10bMdZGeK9fWg6qhQkmACn8YrPpBstuv2DwNXVxayz7EFUcH3JLS4T"
            ],
            [
                '4bf73791a3-18a2c-4340d',
                "ZCfbSdQWxRwPtDKsYerVijmpz86U4k97BuchMGNLT0v35qgX2EaHynJFA1",
            ],
            [
                '4bf73791a3-18a2c-4340d',
                "CdQwz567aTRes8LfNuhv0G4pFXHAiSbjkZYyn9JDBmUtV2cgKPWqEx3Mr1",
            ],
            [
                '4bf73791a3-18a2c-4340d',
                "svXqPfBWTxNytJa2QegbcCLRd6HM4KVm3h1Z5wj89npiDF0SGu7AUzEkrY",
            ]
        ];
    }
}
