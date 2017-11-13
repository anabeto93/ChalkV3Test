<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\External\Infobip;

use App\Application\Adapter\SMSSenderInterface;
use App\Application\Adapter\TranslatorInterface;
use App\Application\Command\External\Infobip\ForwardMessage;
use App\Application\Command\External\Infobip\ForwardMessageHandler;
use App\Application\Command\User\Progression\ValidateSession;
use App\Application\Command\User\Progression\ValidateSessionHandler;
use App\Application\View\SMS\SMSView;
use App\Domain\Exception\Session\SessionNotFoundException;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Session\Validation\Decoder;
use App\Domain\Session\Validation\Encoder;
use App\Domain\User\Progression\Medium;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class ForwardMessageHandlerTest extends TestCase
{
    /** @var ObjectProphecy */
    private $translator;

    /** @var ObjectProphecy */
    private $userRepository;

    /** @var ObjectProphecy */
    private $validateSessionHandler;

    /** @var ObjectProphecy */
    private $SMSSender;

    /** @var string */
    private $phoneNumberSender;

    /** @var ObjectProphecy */
    private $decoder;

    /** @var ObjectProphecy */
    private $encoder;

    /** @var string */
    private $sessionValidationDecodeKey;

    /** @var string */
    private $sessionValidationEncodeKey;

    /** @var string */
    private $frontUrl;

    /** @var string */
    private $frontValidationSessionUrl;

    public function setUp()
    {
        $this->translator = $this->prophesize(TranslatorInterface::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->validateSessionHandler = $this->prophesize(ValidateSessionHandler::class);
        $this->SMSSender = $this->prophesize(SMSSenderInterface::class);
        $this->phoneNumberSender = '+33123123123';
        $this->decoder = $this->prophesize(Decoder::class);
        $this->encoder = $this->prophesize(Encoder::class);
        $this->sessionValidationDecodeKey = 'decodeKey';
        $this->sessionValidationEncodeKey = 'encodeKey';
        $this->frontUrl = 'https://front.url/';
        $this->frontValidationSessionUrl = '#/toto/{unlockCode}';
    }

    public function testHandleNoResult()
    {
        $payload = '{}';

        $command = new ForwardMessage($payload);
        $handler = new ForwardMessageHandler(
            $this->translator->reveal(),
            $this->userRepository->reveal(),
            $this->validateSessionHandler->reveal(),
            $this->SMSSender->reveal(),
            $this->phoneNumberSender,
            $this->decoder->reveal(),
            $this->encoder->reveal(),
            $this->sessionValidationDecodeKey,
            $this->sessionValidationEncodeKey,
            $this->frontUrl,
            $this->frontValidationSessionUrl
        );

        $this->translator->trans(Argument::any())->shouldNotBeCalled();
        $this->decoder->getUserUuidFromCode(Argument::any())->shouldNotBeCalled();
        $this->decoder->getSessionUuidFromCode(Argument::any())->shouldNotBeCalled();
        $this->userRepository->findByUuid(Argument::any())->shouldNotBeCalled();
        $this->validateSessionHandler->handle(Argument::any())->shouldNotBeCalled();
        $this->encoder->getUnlockCodeForSession(Argument::any())->shouldNotBeCalled();
        $this->SMSSender->send(Argument::any())->shouldNotBeCalled();

        $handler->handle($command);
    }

    public function testHandleUnknownUser()
    {
        $payload = '{
          "results": [
            {
              "from": "33123456789",
              "text": "thisIsFakeUuidEncodedThatShouldNotWorkTest Hello world"
            }
          ],
          "messageCount": 1,
          "pendingMessageCount": 0
        }';

        $command = new ForwardMessage($payload);
        $handler = new ForwardMessageHandler(
            $this->translator->reveal(),
            $this->userRepository->reveal(),
            $this->validateSessionHandler->reveal(),
            $this->SMSSender->reveal(),
            $this->phoneNumberSender,
            $this->decoder->reveal(),
            $this->encoder->reveal(),
            $this->sessionValidationDecodeKey,
            $this->sessionValidationEncodeKey,
            $this->frontUrl,
            $this->frontValidationSessionUrl
        );


        $this->decoder
            ->getUserUuidFromCode('decodeKey', 'thisIsFakeUuidEncodedThatShouldNotWorkTest')
            ->shouldBeCalled()
            ->willReturn('abcdef')
        ;
        $this->userRepository
            ->findByUuid('abcdef')
            ->shouldBeCalled()
            ->willReturn(null)
        ;
        $this->translator->trans(Argument::any())->shouldNotBeCalled();
        $this->decoder->getSessionUuidFromCode(Argument::any())->shouldNotBeCalled();
        $this->encoder->getUnlockCodeForSession(Argument::any())->shouldNotBeCalled();
        $this->validateSessionHandler->handle(Argument::any())->shouldNotBeCalled();
        $this->SMSSender->send(Argument::any())->shouldNotBeCalled();

        $handler->handle($command);
    }

    public function testHandleUnknownSession()
    {
        $user = $this->prophesize(User::class);
        $payload = '{
          "results": [
            {
              "from": "33123456789",
              "text": "vUDH7cyFwzy7UEwccyayENc7ayxSXHSEcxccLzcyFF Hello world"
            }
          ],
          "messageCount": 1,
          "pendingMessageCount": 0
        }';

        $command = new ForwardMessage($payload);
        $handler = new ForwardMessageHandler(
            $this->translator->reveal(),
            $this->userRepository->reveal(),
            $this->validateSessionHandler->reveal(),
            $this->SMSSender->reveal(),
            $this->phoneNumberSender,
            $this->decoder->reveal(),
            $this->encoder->reveal(),
            $this->sessionValidationDecodeKey,
            $this->sessionValidationEncodeKey,
            $this->frontUrl,
            $this->frontValidationSessionUrl
        );


        $this->decoder
            ->getUserUuidFromCode('decodeKey', 'vUDH7cyFwzy7UEwccyayENc7ayxSXHSEcxccLzcyFF')
            ->shouldBeCalled()
            ->willReturn('abcdef')
        ;
        $this->userRepository
            ->findByUuid('abcdef')
            ->shouldBeCalled()
            ->willReturn($user->reveal())
        ;
        $this->decoder
            ->getSessionUuidFromCode('decodeKey', 'vUDH7cyFwzy7UEwccyayENc7ayxSXHSEcxccLzcyFF')
            ->shouldBeCalled()
            ->willReturn('a6342f0bf7-a2aae-5a488')
        ;

        $this->validateSessionHandler
            ->handle(new ValidateSession($user->reveal(), 'a6342f0bf7-a2aae-5a488', Medium::SMS))
            ->shouldBeCalled()
            ->willThrow(SessionNotFoundException::class)
        ;
        $this->encoder->getUnlockCodeForSession(Argument::any())->shouldNotBeCalled();
        $this->translator->trans(Argument::any())->shouldNotBeCalled();
        $this->SMSSender->send(Argument::any())->shouldNotBeCalled();

        $handler->handle($command);
    }

    public function testHandle()
    {
        $user = $this->prophesize(User::class);
        $user->getUuid()->willReturn('abcdef');
        $user->getLocale()->willReturn('fr');
        $payload = '{
          "results": [
            {
              "from": "33123456789",
              "text": "vUDH7cyFwzy7UEwccyayENc7ayxSXHSEcxccLzcyFF Hello world"
            }
          ],
          "messageCount": 1,
          "pendingMessageCount": 0
        }';

        $command = new ForwardMessage($payload);
        $handler = new ForwardMessageHandler(
            $this->translator->reveal(),
            $this->userRepository->reveal(),
            $this->validateSessionHandler->reveal(),
            $this->SMSSender->reveal(),
            $this->phoneNumberSender,
            $this->decoder->reveal(),
            $this->encoder->reveal(),
            $this->sessionValidationDecodeKey,
            $this->sessionValidationEncodeKey,
            $this->frontUrl,
            $this->frontValidationSessionUrl
        );

        $this->decoder
            ->getUserUuidFromCode($this->sessionValidationDecodeKey, 'vUDH7cyFwzy7UEwccyayENc7ayxSXHSEcxccLzcyFF')
            ->shouldBeCalled()
            ->willReturn('abcdef')
        ;
        $this->userRepository
            ->findByUuid('abcdef')
            ->shouldBeCalled()
            ->willReturn($user->reveal())
        ;
        $this->decoder
            ->getSessionUuidFromCode($this->sessionValidationDecodeKey, 'vUDH7cyFwzy7UEwccyayENc7ayxSXHSEcxccLzcyFF')
            ->shouldBeCalled()
            ->willReturn('a6342f0bf7-a2aae-5a488')
        ;

        $this->validateSessionHandler
            ->handle(new ValidateSession($user->reveal(), 'a6342f0bf7-a2aae-5a488', Medium::SMS))
            ->shouldBeCalled()
        ;
        $this->encoder
            ->getUnlockCodeForSession($this->sessionValidationEncodeKey, 'abcdef', 'a6342f0bf7-a2aae-5a488')
            ->shouldBeCalled()
            ->willReturn(
                'abcdef1234567890abcdef1234567890'
            )
        ;

        $message = 'Response message';
        $this->translator
            ->trans(
                ForwardMessageHandler::TRANSLATION_VALIDATION_CODE_RESPONSE,
                ['%url%' => 'https://front.url/#/toto/abcdef1234567890abcdef1234567890'],
                ForwardMessageHandler::TRANSLATION_DOMAIN,
                'fr'
            )
            ->shouldBeCalled()
            ->willReturn($message)
        ;


        $this->SMSSender->send(new SMSView($this->phoneNumberSender, ['33123456789'], $message))->shouldBeCalled();

        $handler->handle($command);
    }
}
