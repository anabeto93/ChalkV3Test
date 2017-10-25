<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Domain\Session\Import;

use App\Domain\Session\Import\ContentParsedView;
use App\Domain\Session\Import\ContentParser;
use App\Domain\Session\Import\ImageView;
use App\Domain\Uuid\Generator;
use App\Infrastructure\Service\UrlGenerator;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class ContentParserTest extends TestCase
{
    /** @var ObjectProphecy */
    private $urlGenerator;

    /** @var ObjectProphecy */
    private $uniqIdGenerator;

    public function setUp()
    {
        $this->urlGenerator = $this->prophesize(UrlGenerator::class);
        $this->uniqIdGenerator = $this->prophesize(Generator::class);
    }

    public function testParse()
    {
        $this->urlGenerator->getBaseUrl()->shouldBeCalled()->willReturn('http://api.chalkboardedu.dev');
        $this->uniqIdGenerator->getRandomUniqId()->shouldBeCalled()->willReturn('random_string');
        $contentParser = new ContentParser($this->urlGenerator->reveal(), $this->uniqIdGenerator->reveal());
        $result = $contentParser->parse(__DIR__ . '/index.html', '/image/location');

        $expectedContent = '
    <h1>Hello world</h1>
    <div>
        <img src="http://api.chalkboardedu.dev/image/location/random_string_test.jpg" style="width:100%" />
    </div>
    <p>Lorem ipsum</p>
    <sidebar>
        <img style="width:100%" src="http://api.chalkboardedu.dev/image/location/random_string_sidebar.jpg"/>
    </sidebar>
    <footer>
        <h4>Good bye</h4>
        <div class="footer">
            <div class="footer-bis">
                <img src="http://api.chalkboardedu.dev/image/location/random_string_footer.jpg"/>
            </div>
        </div>
    </footer>
';

        $expected = new ContentParsedView(
            $expectedContent,
            [
                new ImageView('<img src="test.jpg', 'random_string_test.jpg', 'test.jpg'),
                new ImageView('<img style="width:100%" src="sidebar.jpg', 'random_string_sidebar.jpg', 'sidebar.jpg'),
                new ImageView('<img src="footer.jpg', 'random_string_footer.jpg', 'footer.jpg'),
            ]
        );

        $this->assertEquals($expected, $result);
    }
}
