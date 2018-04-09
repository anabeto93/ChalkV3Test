<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 09/04/2018
 * Time: 12:38
 */

namespace Tests\Ui\Admin\Action\Cohort;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Cohort\Delete;
use App\Domain\Model\Cohort;
use App\Domain\Model\Course;
use App\Domain\Model\Institution;
use App\Ui\Admin\Action\Cohort\DeleteAction;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class DeleteActionTest extends TestCase {
    /** @var ObjectProphecy */
    private $commandBus;

    /** @var ObjectProphecy */
    private $flashBag;

    /** @var ObjectProphecy */
    private $router;

    public function setUp() {
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->flashBag = $this->prophesize(FlashBagInterface::class);
        $this->router = $this->prophesize(RouterInterface::class);
    }

    public function testInvokeException() {
        $this->setExpectedException(NotFoundHttpException::class);
        $institution1 = $this->prophesize(Institution::class);
        $institution1->getName()->shouldBeCalled()->willReturn('name 1');
        $institution2 = $this->prophesize(Institution::class);
        $cohort = $this->prophesize(Cohort::class);
        $cohort->getTitle()->shouldBeCalled()->willReturn('title');

        $cohort->getInstitution()->shouldBeCalled()->willReturn($institution2->reveal());

        $deleteAction = new DeleteAction(
            $this->commandBus->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal()
        );

        $deleteAction($institution1->reveal(), $cohort->reveal());
    }

    public function testInvokeError() {
        $institution = $this->prophesize(Institution::class);
        $institution->getId()->shouldBeCalled()->willReturn(123);
        $cohort = $this->prophesize(Cohort::class);
        $course = $this->prophesize(Course::class);

        $cohort->getInstitution()->shouldBeCalled()->willReturn($institution->reveal());
        $cohort->getCourses()->shouldBeCalled()->willReturn([$course->reveal()]);

        $this->router->generate(DeleteAction::ROUTE_REDIRECT_AFTER_SUCCESS, ['institution' => 123])
                    ->shouldBeCalled()
                    ->willReturn('/admin/institutions/123/cohorts');

        $this->flashBag->add('error', 'flash.admin.cohort.delete.error')->shouldBeCalled();

        $deleteAction = new DeleteAction(
            $this->commandBus->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal()
        );

        $deleteAction($institution->reveal(), $cohort->reveal());
    }

    public function testInvoke() {
        $institution = $this->prophesize(Institution::class);
        $institution->getId()->shouldBeCalled()->willReturn(123);
        $cohort = $this->prophesize(Cohort::class);

        $cohort->getInstitution()->shouldBeCalled()->willReturn($institution->reveal());
        $cohort->getCourses()->shouldBeCalled()->willReturn([]);

        $this->commandBus->handle(new Delete($cohort->reveal()))->shouldBeCalled();
        $this->router->generate(DeleteAction::ROUTE_REDIRECT_AFTER_SUCCESS, ['institution' => 123])
                    ->shouldBeCalled()
                    ->willReturn('/admin/institutions/123/cohorts');
        $this->flashBag->add('success', 'flash.admin.cohort.delete.success')->shouldBeCalled();

        $deleteAction = new DeleteAction(
            $this->commandBus->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal()
        );

        $deleteAction($institution->reveal(), $cohort->reveal());
    }
}