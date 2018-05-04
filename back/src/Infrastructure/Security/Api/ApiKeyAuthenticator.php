<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Security\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function createToken(Request $request, $providerKey): PreAuthenticatedToken
    {
        $key = $this->extractKey($request->headers->get('authorization'));

        if (null === $key) {
            throw new BadCredentialsException('No key provided.');
        }

        return new PreAuthenticatedToken('user.', $key, $providerKey);
    }

    /**
     * {@inheritdoc}
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof ApiKeyUserProvider) {
            throw new \InvalidArgumentException('Wrong user provider.');
        }

        $bearer = $token->getCredentials();
        $bearerArray = explode("~", $bearer);
        $key = $bearerArray[0];
        $issuedAt = sizeof($bearerArray) > 1 ?
            date_create_from_format('U', $bearerArray[1]) :
            null;

        $username = $userProvider->getUsernameForApiToken($key);

        if (null === $username) {
            throw new CustomUserMessageAuthenticationException('API key is not valid.', [], 401);
        }

        $apiUser = $userProvider->loadUserByUsername($username);
        $user = $apiUser->getUser();

        if (!$user->isMultiLogin() && $issuedAt !== null && $issuedAt < $user->getApiTokenIssuedAt()) {
            throw new CustomUserMessageAuthenticationException('API Token is in use on another device.', [], 402);
        }

        return new PreAuthenticatedToken($apiUser, $key, $providerKey, $apiUser->getRoles());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsToken(TokenInterface $token, $providerKey): bool
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse([
            'errors' => [
                ['message' => strtr($exception->getMessageKey(), $exception->getMessageData())],
            ]
        ], $exception->getCode() ? $exception->getCode() : 401);
    }

    private function extractKey(string $header = null): ?string
    {
        if ($header === null) {
            return null;
        }

        $matches = [];

        if (1 === preg_match('/^Bearer (.+)$/', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
