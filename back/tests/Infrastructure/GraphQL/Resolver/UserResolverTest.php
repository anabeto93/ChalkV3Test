<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\GraphQL\Resolver;

use App\Domain\Model\User;
use App\Infrastructure\GraphQL\Resolver\UserResolver;
use App\Infrastructure\Normalizer\UserNormalizer;
use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Sonata\IntlBundle\Templating\Helper\LocaleHelper;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserResolverTest extends TestCase
{
    /** @var ObjectProphecy */
    private $tokenStorage;

    /** @var ObjectProphecy */
    private $userNormalizer;

    /** @var ObjectProphecy */
    private $token;

    public function setUp()
    {
        $this->tokenStorage = $this->prophesize(TokenStorage::class);
        $this->userNormalizer = $this->prophesize(UserNormalizer::class);
        $this->token = $this->prophesize(PreAuthenticatedToken::class);
    }

    public function testResolveNoUser()
    {
        $this->setExpectedException(UserError::class);

        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($this->token->reveal());
        $this->token->getUser()->shouldBeCalled()->willReturn(null);

        $userResolver = new UserResolver(
            $this->tokenStorage->reveal(),
            $this->userNormalizer->reveal()
        );
        $userResolver->resolveUser();
    }

    public function testResolveUser()
    {
        $user = new User('uuid-user', 'Jean', 'Paul', '+33123123123', 'FR', 34, 'token', new \DateTime());
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
        ]);

        $userResolver = new UserResolver(
            $this->tokenStorage->reveal(),
            $this->userNormalizer->reveal()
        );
        $result = $userResolver->resolveUser();

        $expected = [
            'uuid' => 'uuid-user',
            'firstName' => 'Jean',
            'lastName' => 'Paul',
            'phoneNumber' => '+33123123123',
            'countryCode' => 'FR',
            'country' => 'France',
        ];

        $this->assertEquals($expected, $result);
    }
}
