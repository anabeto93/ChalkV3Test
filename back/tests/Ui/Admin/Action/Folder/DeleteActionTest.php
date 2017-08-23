<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\Folder;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Folder\Delete;
use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Ui\Admin\Action\Folder\DeleteAction;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class DeleteActionTest extends TestCase
{
    /** @var ObjectProphecy */
    private $router;

    /** @var ObjectProphecy */
    private $commandBus;

    /** @var ObjectProphecy */
    private $flashBag;

    public function setUp()
    {
        $this->router = $this->prophesize(RouterInterface::class);
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->flashBag = $this->prophesize(FlashBagInterface::class);
    }

    public function testInvokeException()
    {
        $this->setExpectedException(NotFoundHttpException::class);
        $folder = $this->prophesize(Folder::class);
        $course = $this->prophesize(Course::class);
        $course2 = $this->prophesize(Course::class);
        $folder->getCourse()->shouldBeCalled()->willReturn($course2->reveal());

        $deleteAction = new DeleteAction(
            $this->router->reveal(),
            $this->commandBus->reveal(),
            $this->flashBag->reveal()
        );
        $deleteAction($course->reveal(), $folder->reveal());
    }

    public function testInvoke()
    {
        $folder = $this->prophesize(Folder::class);
        $course = $this->prophesize(Course::class);
        $course->getId()->shouldBeCalled()->willReturn(123);
        $folder->getCourse()->shouldBeCalled()->willReturn($course->reveal());

        $this->commandBus->handle(new Delete($folder->reveal()))->shouldBeCalled();
        $this->router
            ->generate(DeleteAction::ROUTE_REDIRECT_AFTER_SUCCESS, [
                'course' => 123,
            ])
            ->shouldBeCalled()
            ->willReturn('/admin/course/123/folder')
        ;
        $this->flashBag->add('success', 'flash.admin.folder.delete.success');

        $deleteAction = new DeleteAction(
            $this->router->reveal(),
            $this->commandBus->reveal(),
            $this->flashBag->reveal()
        );
        $deleteAction($course->reveal(), $folder->reveal());
    }
}
