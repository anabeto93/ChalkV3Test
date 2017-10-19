<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\User\Import;

use App\Infrastructure\HttpFoundation\CsvFileResponse;
use App\Ui\Admin\Action\User\Import\SampleAction;
use PHPUnit\Framework\TestCase;

class SampleActionTest extends TestCase
{
    public function testInvoke()
    {
        $action = new SampleAction();
        $result = $action();

        $this->assertInstanceOf(CsvFileResponse::class, $result);
    }
}
