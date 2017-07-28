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
use App\Application\Command\Session\Create;
use App\Domain\Exception\Session\Import\ImageFileNotPresentException;
use App\Domain\Exception\Session\Import\ImageWithoutSrcException;
use App\Domain\Exception\Session\Import\IndexFileNotContainInZipException;
use App\Domain\Model\Course;
use App\Ui\Admin\Form\Type\Session\CreateType;
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
    const TEMPLATE = 'Admin/Session/create.html.twig';
    const ROUTE_REDIRECT_AFTER_SUCCESS = 'admin_session_list';

    const TRANS_VALIDATOR_INDEX_NOT_PRESENT = 'validator.session.import.indexNotPresent';
    const TRANS_VALIDATOR_IMAGE_WITHOUT_SRC = 'validator.session.import.imageWithoutSrc';
    const TRANS_VALIDATOR_IMAGE_NOT_FOUND = 'validator.session.import.imageNotFound';

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
     *
     * @return Response
     */
    public function __invoke(Request $request, Course $course): Response
    {
        $create = new Create($course);
        $form = $this->formFactory->create(CreateType::class, $create, [
            'course' => $course,
            'submit' => true,
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle($create);
                $this->flashBag->add('success', 'flash.admin.session.create.success');

                return new RedirectResponse($this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS, [
                    'course' => $course->getId(),
                ]));
            } catch (IndexFileNotContainInZipException $exception) {
                $form->get('content')->addError(new FormError(
                    $this->translator->trans(self::TRANS_VALIDATOR_INDEX_NOT_PRESENT, [], 'validators')
                ));
            } catch (ImageWithoutSrcException $exception) {
                $form->get('content')->addError(new FormError(
                    $this->translator->trans(self::TRANS_VALIDATOR_IMAGE_WITHOUT_SRC, [], 'validators')
                ));
            } catch (ImageFileNotPresentException $exception) {
                $form->get('content')->addError(new FormError(
                    $this->translator->trans(
                        self::TRANS_VALIDATOR_IMAGE_NOT_FOUND,
                        ['%image%' => $exception->fileName],
                        'validators'
                    )
                ));
            }
        }

        return $this->engine->renderResponse(self::TEMPLATE, [
            'course' => $course,
            'form'   => $form->createView()
        ]);
    }
}
