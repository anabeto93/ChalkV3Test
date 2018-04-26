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
        $key = substr($bearer, 0, 6);
        $issuedAt = substr($bearer, 6) ? date('Y-m-d H:i:s', substr($bearer, 6)): null;

        $username = $userProvider->getUsernameForApiToken($key);

        if (null === $username) {
            throw new CustomUserMessageAuthenticationException('API key is not valid.');
        }

        $user = $userProvider->loadUserByUsername($username);

        if ($issuedAt !== null && $issuedAt < $user->getUser()->getApiTokenIssuedAt()) {
            throw new CustomUserMessageAuthenticationException('API Token is in use on another device.');
        }

        return new PreAuthenticatedToken($user, $key, $providerKey, $user->getRoles());
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
        ], 401);
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
