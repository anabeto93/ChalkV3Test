<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 05/04/2018
 * Time: 13:53
 */

namespace Tests\Ui\Admin\Action\Institution;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Institution\Delete;
use App\Domain\Model\Cohort;
use App\Domain\Model\Course;
use App\Domain\Model\Institution;
use App\Ui\Admin\Action\Institution\DeleteAction;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class DeleteActionTest extends TestCase {
    /** @var ObjectProphecy */
    private $router;

    /** @var ObjectProphecy */
    private $commandBus;

    /** @var ObjectProphecy */
    private $flashBag;

    public function setUp() {
        $this->router = $this->prophesize(RouterInterface::class);
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->flashBag = $this->prophesize(FlashBagInterface::class);
    }

    public function testInvokeError() {
        $institution = $this->prophesize(Institution::class);
        $cohort = $this->prophesize(Cohort::class);
        $course = $this->prophesize(Course::class);

        $institution->getCohorts()->shouldBeCalled()->willReturn([$cohort->reveal()]);
        $cohort->getCourses()->shouldBeCalled()->willReturn([$course->reveal()]);

        $this->router->generate('admin_institution_list')
                    ->shouldBeCalled()
                    ->willReturn('/admin/institutions');

        $this->flashBag->add('error', 'flash.admin.institution.delete.error');

        $deleteAction = new DeleteAction(
            $this->router->reveal(),
            $this->commandBus->reveal(),
            $this->flashBag->reveal()
        );
        $deleteAction($institution->reveal());
    }

    public function testInvoke() {
        $institution = $this->prophesize(Institution::class);
        $cohort1 = $this->prophesize(Cohort::class);
        $cohort2 = $this->prophesize(Cohort::class);

        $institution->getCohorts()->shouldBeCalled()
                    ->willReturn([$cohort1->reveal(), $cohort2->reveal()]);
        $cohort1->getCourses()->shouldBeCalled();
        $cohort2->getCourses()->shouldBeCalled();

        $this->commandBus->handle(new Delete($institution->reveal()))->shouldBeCalled();
        $this->router->generate('admin_institution_list')->shouldBeCalled()->willReturn('/admin/institutions');
        $this->flashBag->add('success', 'flash.admin.institution.delete.success');


        $deleteAction = new DeleteAction(
            $this->router->reveal(),
            $this->commandBus->reveal(),
            $this->flashBag->reveal()
        );
        $deleteAction($institution->reveal());
    }
}