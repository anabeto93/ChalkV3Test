<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\GraphQL\Resolver;

use App\Domain\Model\Session;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Infrastructure\GraphQL\Resolver\SessionResolver;
use App\Infrastructure\Normalizer\SessionNormalizer;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Argument as ProphecyArgument;

class SessionResolverTest extends TestCase
{
    /** @var ObjectProphecy */
    private $sessionRepository;

    /** @var ObjectProphecy */
    private $sessionNormalizer;

    public function setUp()
    {
        $this->sessionRepository = $this->prophesize(SessionRepositoryInterface::class);
        $this->sessionNormalizer = $this->prophesize(SessionNormalizer::class);
    }

    public function testNotFound()
    {
        $this->setExpectedException(UserError::class);

        $this->sessionRepository->getByUuid('not-found')->shouldBeCalled()->willReturn(null);
        $this->sessionNormalizer->normalize(ProphecyArgument::any())->shouldNotBeCalled();

        $resolver = new SessionResolver(
            $this->sessionRepository->reveal(),
            $this->sessionNormalizer->reveal()
        );
        $resolver->resolveSession(new Argument(['uuid' => 'not-found']));
    }

    public function testResolve()
    {
        $session = $this->prophesize(Session::class);

        $expected = [
            'uuid' => 'uuid',
            'rank' => 5,
            'title' => 'session title',
            'content' => 'this is the content',
        ];

        $this->sessionRepository->getByUuid('uuid')->shouldBeCalled()->willReturn($session->reveal());
        $this->sessionNormalizer
            ->normalize($session->reveal())
            ->shouldBeCalled()
            ->willReturn([
                'uuid' => 'uuid',
                'rank' => 5,
                'title' => 'session title',
                'content' => 'this is the content',
            ])
        ;

        $resolver = new SessionResolver(
            $this->sessionRepository->reveal(),
            $this->sessionNormalizer->reveal()
        );
        $result = $resolver->resolveSession(new Argument(['uuid' => 'uuid']));

        $this->assertEquals($expected, $result);
    }
}
