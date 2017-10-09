<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\External\Infobip;

use App\Application\Adapter\SMSSenderInterface;
use App\Application\Command\User\Progression\ValidateSession;
use App\Application\Command\User\Progression\ValidateSessionHandler;
use App\Application\View\SMS\SMSView;
use App\Domain\Exception\Session\ValidateSession\SessionNotAccessibleForThisUserException;
use App\Domain\Exception\Session\ValidateSession\SessionNotFoundException;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Session\Validation\Decoder;
use App\Domain\Session\Validation\Encoder;
use App\Domain\User\Progression\Medium;

class ForwardMessageHandler
{
    /** @var Decoder */
    private $decoder;

    /** @var Encoder */
    private $encoder;

    /** @var string */
    private $sessionValidationDecodeKey;

    /** @var string */
    private $sessionValidationEncodeKey;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var ValidateSessionHandler */
    private $validateSessionHandler;

    /** @var SMSSenderInterface */
    private $SMSSender;

    /** @var string */
    private $phoneNumberSender;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param ValidateSessionHandler  $validateSessionHandler
     * @param SMSSenderInterface      $SMSSender
     * @param string                  $phoneNumberSender
     * @param Decoder                 $decoder
     * @param Encoder                 $encoder
     * @param string                  $sessionValidationDecodeKey
     * @param string                  $sessionValidationEncodeKey
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        ValidateSessionHandler $validateSessionHandler,
        SMSSenderInterface $SMSSender,
        string $phoneNumberSender,
        Decoder $decoder,
        Encoder $encoder,
        string $sessionValidationDecodeKey,
        string $sessionValidationEncodeKey
    ) {
        $this->userRepository             = $userRepository;
        $this->validateSessionHandler     = $validateSessionHandler;
        $this->SMSSender                  = $SMSSender;
        $this->phoneNumberSender          = $phoneNumberSender;
        $this->decoder                    = $decoder;
        $this->encoder                    = $encoder;
        $this->sessionValidationDecodeKey = $sessionValidationDecodeKey;
        $this->sessionValidationEncodeKey = $sessionValidationEncodeKey;
    }

    /**
     * @param ForwardMessage $forwardMessage
     */
    public function handle(ForwardMessage $forwardMessage)
    {
        $response = json_decode($forwardMessage->payload, true);

        if ($response === null || !isset($response['results'])) {
            return;
        }

        foreach ($response['results'] as $message) {
            $from = $message['from'] ?? null;
            $body = $message['text'] ?? '';

            $code = mb_substr(trim($body), 0, 42);

            $userUuid = $this->decoder->getUserUuidFromCode($this->sessionValidationDecodeKey, $code);

            $user = $this->userRepository->findByUuid($userUuid);

            // Message not usable
            if ($user === null) {
                continue;
            }

            $sessionUuid = $this->decoder->getSessionUuidFromCode($this->sessionValidationDecodeKey, $code);

            try {
                $this->validateSessionHandler->handle(new ValidateSession($user, $sessionUuid, Medium::SMS));
            } catch (SessionNotAccessibleForThisUserException $exception) {
                continue;
            } catch (SessionNotFoundException $exception) {
                continue;
            }

            $unlockCode = $this->encoder->getUnlockCodeForSession(
                $this->sessionValidationEncodeKey,
                $user->getUuid(),
                $sessionUuid
            );

            if ($from === null) {
                $from = $user->getPhoneNumber();
            }

            $this->SMSSender->send(
                new SMSView($this->phoneNumberSender, [$from], $unlockCode)
            );
        }
    }
}
