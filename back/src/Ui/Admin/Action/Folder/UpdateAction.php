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
use App\Application\Command\Folder\Update;
use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Ui\Admin\Form\Type\Folder\UpdateType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class UpdateAction
{
    const TEMPLATE = 'Admin/Folder/update.html.twig';
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
     * @param Folder  $folder
     *
     * @return Response
     */
    public function __invoke(Request $request, Course $course, Folder $folder): Response
    {
        if ($course !== $folder->getCourse()) {
            throw new NotFoundHttpException(
                sprintf('The folder %s is not on the course %s', $folder->getId(), $course->getId())
            );
        }
        $update = new Update($folder);
        $form = $this->formFactory->create(UpdateType::class, $update, [
            'submit' => true,
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($update);

            $this->flashBag->add('success', 'flash.admin.folder.update.success');

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
