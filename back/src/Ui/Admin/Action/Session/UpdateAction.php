<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\Session;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Adapter\TranslatorInterface;
use App\Application\Command\Session\Update;
use App\Domain\Exception\Session\Import\ImageFileNotPresentException;
use App\Domain\Exception\Session\Import\IndexFileNotContainInZipException;
use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Ui\Admin\Form\Type\Session\UpdateType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class UpdateAction
{
    const TEMPLATE = 'Admin/Session/update.html.twig';
    const ROUTE_REDIRECT_AFTER_SUCCESS = 'admin_session_list';

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
     * @param Course  $course
     * @param Session $session
     *
     * @return Response
     */
    public function __invoke(Request $request, Course $course, Session $session): Response
    {
        if ($course !== $session->getCourse()) {
            throw new NotFoundHttpException('This session is not in this course');
        }

        $update = new Update($session);
        $form = $this->formFactory->create(UpdateType::class, $update, [
            'course' => $course,
            'submit' => true,
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle($update);
                $this->flashBag->add('success', 'flash.admin.session.update.success');

                return new RedirectResponse($this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS, [
                    'course' => $course->getId(),
                ]));
            } catch (IndexFileNotContainInZipException $exception) {
                $form->get('content')->addError(new FormError(
                    $this->translator->trans(CreateAction::TRANS_VALIDATOR_INDEX_NOT_PRESENT, [], 'validators')
                ));
            } catch (ImageFileNotPresentException $exception) {
                $form->get('content')->addError(new FormError(
                    $this->translator->trans(
                        CreateAction::TRANS_VALIDATOR_IMAGE_NOT_FOUND,
                        ['%image%' => $exception->fileName],
                        'validators'
                    )
                ));
            }
        }

        return $this->engine->renderResponse(self::TEMPLATE, [
            'course'  => $course,
            'form'    => $form->createView(),
            'session' => $session,
        ]);
    }
}
