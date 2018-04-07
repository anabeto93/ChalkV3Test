<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 06/04/2018
 * Time: 17:23
 */

namespace Tests\Ui\Admin\Action\Cohort;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Cohort\Update;
use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;
use App\Ui\Admin\Action\Cohort\UpdateAction;
use App\Ui\Admin\Form\Type\Cohort\UpdateType;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UpdateActionTest extends TestCase {
    /** @var ObjectProphecy */
    private $engine;

    /** @var ObjectProphecy */
    private $commandBus;

    /** @var ObjectProphecy */
    private $formFactory;

    /** @var ObjectProphecy */
    private $flashBag;

    /** @var ObjectProphecy */
    private $router;

    /** @var ObjectProphecy */
    private $translator;

    public function setUp() {
        $this->engine = $this->prophesize(EngineInterface::class);
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->formFactory = $this->prophesize(FormFactoryInterface::class);
        $this->flashBag = $this->prophesize(FlashBagInterface::class);
        $this->router = $this->prophesize(RouterInterface::class);
        $this->translator = $this->prophesize(TranslatorInterface::class);
    }

    public function testInvoke() {
        //Context
        $institution = $this->prophesize(Institution::class);
        $cohort = new Cohort('uuid', $institution->reveal(), 'title', new \DateTime());
        $request = new Request();
        $response = new Response();
        $update = new Update($institution->reveal(), $cohort);

        $form = $this->prophesize(FormInterface::class);
        $formView = $this->prophesize(FormView::class);
        $form->createView()->shouldBeCalled()->willReturn($formView->reveal());

        //Mock
        $this->engine->renderResponse(
            "Admin/Cohort/update.html.twig", [
                'institution' => $institution->reveal(),
                'form' => $formView
            ]
        )->shouldBeCalled()->willReturn($response);

        $this->formFactory->create(UpdateType::class, $update, ['submit' => true])
                        ->shouldBeCalled()
                        ->willReturn($form->reveal());

        $form->handleRequest($request)->shouldBeCalled()->willReturn($form->reveal());
        $form->isSubmitted()->shouldBeCalled()->willReturn(false);

        //Action
        $updateAction = new UpdateAction(
            $this->engine->reveal(),
            $this->commandBus->reveal(),
            $this->formFactory->reveal(),
            $this->flashBag->reveal(),
            $this->router->reveal(),
            $this->translator->reveal()
        );

        $result = $updateAction($request, $institution->reveal(), $cohort);

        $this->assertInstanceOf(Response::class, $result);
    }
}