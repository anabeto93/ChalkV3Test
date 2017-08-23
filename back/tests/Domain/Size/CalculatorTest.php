<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Domain\Size;

use App\Domain\Size\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    /**
     * @dataProvider provideData
     *
     * @param string $toCalculate
     * @param int    $size
     */
    public function testCalculateSize(string $toCalculate, int $size)
    {
        $calculator = new Calculator();
        $result = $calculator->calculateSize($toCalculate);

        $this->assertEquals($size, $result);
    }

    /**
     * @return array
     */
    public function provideData(): array
    {
        return [
            ['string', 6],
            ['test', 4],
            ['aaaaa aaaaa', 11],
            ['this is a test', 14],
            ['🥐String % with / spéci@l chars 🚀 t0 determin€ the lènG|h', 66]
        ];
    }
}
