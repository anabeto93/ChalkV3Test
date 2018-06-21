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
use App\Application\Command\User\Update;
use App\Domain\Exception\User\PhoneNumberAlreadyUsedException;
use App\Domain\Model\Institution;
use App\Domain\Model\User;
use App\Ui\Admin\Form\Type\User\UpdateType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UpdateAction
{
    const TEMPLATE = 'Admin/User/update.html.twig';
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
     * @param Institution $institution
     * @param User    $user
     *
     * @return Response
     */
    public function __invoke(Request $request, Institution $institution, User $user): Response
    {
        if ($institution !== $user->getInstitution()) {
            throw new NotFoundHttpException(
                sprintf('The user %s does not exist in the institution %s', $user->getId(), $institution->getId())
            );
        }

        $update = new Update($user);
        $form = $this->formFactory->create(UpdateType::class, $update, [
            'submit' => true,
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle($update);
                $this->flashBag->add('success', 'flash.admin.user.update.success');

                return new RedirectResponse($this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS, [
                    'institution' => $institution->getId()
                ]));
            } catch (PhoneNumberAlreadyUsedException $exception) {
                $form->get('phoneNumber')->addError(new FormError(
                    $this->translator->trans(self::TRANS_VALIDATOR_PHONE_NUMBER_USED, [], 'validators')
                ));
            }
        }

        return $this->engine->renderResponse(self::TEMPLATE, [
            'institution' => $institution,
            'form' => $form->createView()
        ]);
    }
}
