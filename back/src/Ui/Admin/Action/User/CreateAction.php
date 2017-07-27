<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\User;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\User\Create;
use App\Domain\Exception\User\PhoneNumberAlreadyUsedException;
use App\Ui\Admin\Form\Type\User\CreateType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CreateAction
{
    const TEMPLATE = 'Admin/User/create.html.twig';
    const ROUTE_REDIRECT_AFTER_SUCCESS = 'admin_user_list';
    const TRANS_VALIDATOR_PHONE_NUMBER_USED = 'validator.phoneNumber.alreadyUsed';

    /** @var EngineInterface */
    private $engine;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var FlashBagInterface */
    private $flashBag;

    /** @var RouterInterface */
    private $router;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * @param EngineInterface      $engine
     * @param CommandBusInterface  $commandBus
     * @param FormFactoryInterface $formFactory
     * @param FlashBagInterface    $flashBag
     * @param RouterInterface      $router
     * @param TranslatorInterface  $translator
     */
    public function __construct(
        EngineInterface $engine,
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        RouterInterface $router,
        TranslatorInterface $translator
    ) {
        $this->engine = $engine;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function __invoke(Request $request): Response
    {
        $create = new Create();
        $form = $this->formFactory->create(CreateType::class, $create, [
            'submit' => true,
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle($create);
                $this->flashBag->add('success', 'flash.admin.user.create.success');

                return new RedirectResponse($this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS));
            } catch (PhoneNumberAlreadyUsedException $exception) {
                $form->get('phoneNumber')->addError(new FormError(
                    $this->translator->trans(self::TRANS_VALIDATOR_PHONE_NUMBER_USED, [], 'validators')
                ));
            }
        }

        return $this->engine->renderResponse(self::TEMPLATE, [
            'form' => $form->createView()
        ]);
    }
}
