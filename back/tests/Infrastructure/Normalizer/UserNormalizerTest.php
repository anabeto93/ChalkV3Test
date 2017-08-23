<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Normalizer;

use App\Domain\Model\User;
use App\Infrastructure\Normalizer\UserNormalizer;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Sonata\IntlBundle\Templating\Helper\LocaleHelper;

class UserNormalizerTest extends TestCase
{
    /** @var ObjectProphecy */
    private $localeHelper;

    public function setUp()
    {
        $this->localeHelper = $this->prophesize(LocaleHelper::class);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param User  $userInput
     * @param array $expected
     */
    public function testNormalize(User $userInput, array $expected)
    {
        $this->localeHelper->country('FR')->shouldBeCalled()->willReturn('France');

        $normalizer = new UserNormalizer($this->localeHelper->reveal());
        $result = $normalizer->normalize($userInput);

        $this->assertEquals($expected, $result);
    }

    public function dataProvider(): array
    {
        return [
            [
                new User('uuid-user', 'Jean', 'Paul', '+33123123123', 'FR', 'fr', 34, new \DateTime()),
                [
                    'uuid' => 'uuid-user',
                    'firstName' => 'Jean',
                    'lastName' => 'Paul',
                    'phoneNumber' => '+33123123123',
                    'countryCode' => 'FR',
                    'country' => 'France',
                    'locale' => 'fr',
                ]
            ],
            [
                new User('Other-UUID', 'TRUC', 'Muche', '+33987987987', 'FR', 'fr', 34, new \DateTime()),
                [
                    'uuid' => 'Other-UUID',
                    'firstName' => 'TRUC',
                    'lastName' => 'Muche',
                    'phoneNumber' => '+33987987987',
                    'countryCode' => 'FR',
                    'country' => 'France',
                    'locale' => 'fr',
                ]
            ],
        ];
    }
}
