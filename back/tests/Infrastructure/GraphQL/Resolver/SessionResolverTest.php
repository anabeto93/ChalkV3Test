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
use App\Domain\Model\User;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Repository\User\ProgressionRepositoryInterface;
use App\Infrastructure\GraphQL\Resolver\SessionResolver;
use App\Infrastructure\Normalizer\SessionNormalizer;
use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Argument as ProphecyArgument;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SessionResolverTest extends TestCase
{
    /** @var ObjectProphecy */
    private $sessionRepository;

    /** @var ObjectProphecy */
    private $sessionNormalizer;

    /** @var ObjectProphecy */
    private $tokenStorage;

    /** @var ObjectProphecy */
    private $progressionRepository;

    public function setUp()
    {
        $this->sessionRepository = $this->prophesize(SessionRepositoryInterface::class);
        $this->sessionNormalizer = $this->prophesize(SessionNormalizer::class);
        $this->tokenStorage = $this->prophesize(TokenStorageInterface::class);
        $this->progressionRepository = $this->prophesize(ProgressionRepositoryInterface::class);
    }

    public function testNotFound()
    {
        $this->setExpectedException(UserError::class);
        $user = $this->prophesize(User::class);

        $this->sessionRepository->getByUuid('not-found')->shouldBeCalled()->willReturn(null);
        $this->sessionNormalizer->normalize(ProphecyArgument::any())->shouldNotBeCalled();
        $token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token->reveal());
        $token->getUser()->willReturn(new ApiUserAdapter($user->reveal()));

        $resolver = new SessionResolver(
            $this->tokenStorage->reveal(),
            $this->sessionRepository->reveal(),
            $this->sessionNormalizer->reveal(),
            $this->progressionRepository->reveal()
        );
        $resolver->resolveSession(new Argument(['uuid' => 'not-found']));
    }

    public function testResolve()
    {
        $session = $this->prophesize(Session::class);
        $session->isEnabled()->willReturn(true);
        $user = $this->prophesize(User::class);

        $expected = [
            'uuid' => 'uuid',
            'rank' => 5,
            'title' => 'session title',
            'content' => 'this is the content',
        ];

        $this->sessionRepository->getByUuid('uuid')->shouldBeCalled()->willReturn($session->reveal());
        $this->sessionNormalizer
            ->normalize($session->reveal(), false)
            ->shouldBeCalled()
            ->willReturn([
                'uuid' => 'uuid',
                'rank' => 5,
                'title' => 'session title',
                'content' => 'this is the content',
            ])
        ;
        $token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token->reveal());
        $token->getUser()->willReturn(new ApiUserAdapter($user->reveal()));

        $this->progressionRepository
            ->findByUserAndSession($user->reveal(), $session->reveal())
            ->shouldBeCalled()
            ->willReturn(null)
        ;

        $resolver = new SessionResolver(
            $this->tokenStorage->reveal(),
            $this->sessionRepository->reveal(),
            $this->sessionNormalizer->reveal(),
            $this->progressionRepository->reveal()
        );
        $result = $resolver->resolveSession(new Argument(['uuid' => 'uuid']));

        $this->assertEquals($expected, $result);
    }
}
