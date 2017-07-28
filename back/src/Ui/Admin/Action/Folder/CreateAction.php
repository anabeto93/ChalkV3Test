<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action\Folder;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Folder\Create;
use App\Domain\Model\Course;
use App\Ui\Admin\Form\Type\Folder\CreateType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class CreateAction
{
    const TEMPLATE = 'Admin/Folder/create.html.twig';
    const ROUTE_REDIRECT_AFTER_SUCCESS = 'admin_folder_list';

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

    /**
     * @param EngineInterface      $engine
     * @param CommandBusInterface  $commandBus
     * @param FormFactoryInterface $formFactory
     * @param FlashBagInterface    $flashBag
     * @param RouterInterface      $router
     */
    public function __construct(
        EngineInterface $engine,
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        RouterInterface $router
    ) {
        $this->engine = $engine;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
        $this->router = $router;
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
            'submit' => true,
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($create);

            $this->flashBag->add('success', 'flash.admin.folder.create.success');

            return new RedirectResponse($this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS, [
                'course' => $course->getId(),
            ]));
        }

        return $this->engine->renderResponse(self::TEMPLATE, [
            'course' => $course,
            'form'   => $form->createView()
        ]);
    }
}
