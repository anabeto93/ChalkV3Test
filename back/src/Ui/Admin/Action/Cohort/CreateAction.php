<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 14:04
 */

namespace App\Ui\Admin\Action\Cohort;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Cohort\Create;
use App\Domain\Model\Institution;
use App\Ui\Admin\Form\Type\Cohort\CreateType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class CreateAction {
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
     * CreateAction constructor.
     * @param EngineInterface $engine
     * @param CommandBusInterface $commandBus
     * @param FormFactoryInterface $formFactory
     * @param FlashBagInterface $flashBag
     * @param RouterInterface $router
     */
    public function __construct(EngineInterface $engine, CommandBusInterface $commandBus, FormFactoryInterface $formFactory, FlashBagInterface $flashBag, RouterInterface $router) {
        $this->engine = $engine;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
        $this->router = $router;
    }

    public function __invoke(Request $request, Institution $institution) {
        $create = new Create($institution);

        $form = $this->formFactory->create(CreateType::class, $create, [
            'submit' => true,
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($create);

            $this->flashBag->add('success', 'flash.admin.cohort.create.success');

            return new RedirectResponse($this->router->generate(
                'admin_cohort_list', ['institution' => $institution->getId()]
            ));
        }

        return $this->engine->renderResponse('Admin/Cohort/create.html.twig', [
            'institution' => $institution,
            'form' => $form->createView()
        ]);
    }
}