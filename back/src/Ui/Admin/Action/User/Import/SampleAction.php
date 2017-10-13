<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\User\Import;

use App\Domain\Charset\Charset;
use App\Infrastructure\HttpFoundation\CsvFileResponse;

class SampleAction
{
    public function __invoke(): CsvFileResponse
    {
        $sample = "firstName;lastName;phoneNumber;country;language
Jean;Paul;+33123123;FR;fr
Kaci;Ernser;+33321312;GH;en";

        return new CsvFileResponse(
            $sample,
            'user-import-sample.csv',
            200,
            [],
            Charset::WINDOWS_1252
        );
    }
}
