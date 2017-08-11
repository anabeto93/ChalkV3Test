<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Ui\Admin\Action\Session;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Session\Delete;
use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Ui\Admin\Action\Session\DeleteAction;
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
        $session = $this->prophesize(Session::class);
        $course = $this->prophesize(Course::class);
        $course2 = $this->prophesize(Course::class);
        $session->getCourse()->shouldBeCalled()->willReturn($course2->reveal());

        $deleteAction = new DeleteAction(
            $this->router->reveal(),
            $this->commandBus->reveal(),
            $this->flashBag->reveal()
        );
        $deleteAction($course->reveal(), $session->reveal());
    }

    public function testInvoke()
    {
        $session = $this->prophesize(Session::class);
        $course = $this->prophesize(Course::class);
        $course->getId()->shouldBeCalled()->willReturn(123);
        $session->getCourse()->shouldBeCalled()->willReturn($course->reveal());

        $this->commandBus->handle(new Delete($session->reveal()))->shouldBeCalled();
        $this->router
            ->generate(DeleteAction::ROUTE_REDIRECT_AFTER_SUCCESS, [
                'course' => 123,
            ])
            ->shouldBeCalled()
            ->willReturn('/admin/course/123/session')
        ;
        $this->flashBag->add('success', 'flash.admin.session.delete.success');

        $deleteAction = new DeleteAction(
            $this->router->reveal(),
            $this->commandBus->reveal(),
            $this->flashBag->reveal()
        );
        $deleteAction($course->reveal(), $session->reveal());
    }
}
