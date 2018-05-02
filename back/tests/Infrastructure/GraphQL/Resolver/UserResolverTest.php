<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\GraphQL\Resolver;

use App\Application\Command\User\TokenIssuedAt;
use App\Application\Command\User\TokenIssuedAtHandler;
use App\Domain\Model\User;
use App\Infrastructure\GraphQL\Resolver\UserResolver;
use App\Infrastructure\Normalizer\UserNormalizer;
use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserResolverTest extends TestCase
{
    /** @var ObjectProphecy */
    private $tokenStorage;

    /** @var ObjectProphecy */
    private $userNormalizer;

    /** @var ObjectProphecy */
    private $tokenIssuedAtHandler;

    /** @var ObjectProphecy */
    private $token;

    public function setUp()
    {
        $this->tokenStorage = $this->prophesize(TokenStorage::class);
        $this->userNormalizer = $this->prophesize(UserNormalizer::class);
        $this->tokenIssuedAtHandler = $this->prophesize(TokenIssuedAtHandler::class);
        $this->token = $this->prophesize(PreAuthenticatedToken::class);
    }

    public function testResolveNoUser()
    {
        $this->setExpectedException(UserError::class);

        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($this->token->reveal());
        $this->token->getUser()->shouldBeCalled()->willReturn(null);

        $userResolver = new UserResolver(
            $this->tokenStorage->reveal(),
            $this->userNormalizer->reveal(),
            $this->tokenIssuedAtHandler->reveal()
        );
        $userResolver->resolveUser(new Argument([]));
    }

    public function testResolveUser()
    {
        $user = new User('uuid-user', 'Jean', 'Paul', '+33123123123', 'FR', 'fr', 34, 'token', new \DateTime());
        $apiUser = new ApiUserAdapter($user);

        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($this->token->reveal());
        $this->token->getUser()->shouldBeCalled()->willReturn($apiUser);
        $this->userNormalizer->normalize($user)->shouldBeCalled()->willReturn([
            'uuid' => 'uuid-user',
            'firstName' => 'Jean',
            'lastName' => 'Paul',
            'phoneNumber' => '+33123123123',
            'countryCode' => 'FR',
            'country' => 'France',
            'locale' => 'fr',
        ]);

        $userResolver = new UserResolver(
            $this->tokenStorage->reveal(),
            $this->userNormalizer->reveal(),
            $this->tokenIssuedAtHandler->reveal()
        );
        $result = $userResolver->resolveUser(new Argument([]));

        $expected = [
            'uuid' => 'uuid-user',
            'firstName' => 'Jean',
            'lastName' => 'Paul',
            'phoneNumber' => '+33123123123',
            'countryCode' => 'FR',
            'country' => 'France',
            'locale' => 'fr',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testResolveUserTokenIssuedAt()
    {
        $user = $this->prophesize(User::class);
        $apiUser = $this->prophesize(ApiUserAdapter::class);

        $apiUser->getUser()->willReturn($user->reveal());

        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($this->token->reveal());
        $this->token->getUser()->shouldBeCalled()->willReturn($apiUser->reveal());

        $tokenIssuedAt = new TokenIssuedAt($user->reveal());
        $tokenIssuedAt->apiTokenIssuedAt = date_create_from_format("U", "1525223975");

        $this->tokenIssuedAtHandler->handle($tokenIssuedAt)->shouldBeCalled();
        $user->getApiTokenIssuedAt()->shouldBeCalled()->willReturn(null);

        $this->userNormalizer->normalize($user)->shouldBeCalled()->willReturn([
            'uuid' => 'uuid-user',
            'firstName' => 'Jean',
            'lastName' => 'Paul',
            'phoneNumber' => '+33123123123',
            'countryCode' => 'FR',
            'country' => 'France',
            'locale' => 'fr',
        ]);

        $userResolver = new UserResolver(
            $this->tokenStorage->reveal(),
            $this->userNormalizer->reveal(),
            $this->tokenIssuedAtHandler->reveal()
        );
        $result = $userResolver->resolveUser(new Argument(["tokenIssuedAt" => "1525223975"]));

        $expected = [
            'uuid' => 'uuid-user',
            'firstName' => 'Jean',
            'lastName' => 'Paul',
            'phoneNumber' => '+33123123123',
            'countryCode' => 'FR',
            'country' => 'France',
            'locale' => 'fr',
        ];

        $this->assertEquals($expected, $result);
    }
}
