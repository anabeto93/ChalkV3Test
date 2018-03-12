<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 04:27
 */

namespace App\Ui\Admin\Action\Institution;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Institution\Update;
use App\Domain\Model\Institution;
use App\Ui\Admin\Form\Type\Institution\UpdateType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class UpdateAction {
    /** @var EngineInterface */
    private $engine;

    /** @var RouterInterface */
    private $router;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * UpdateAction constructor.
     * @param EngineInterface $engine
     * @param RouterInterface $router
     * @param CommandBusInterface $commandBus
     * @param FormFactoryInterface $formFactory
     * @param FlashBagInterface $flashBag
     */
    public function __construct(EngineInterface $engine, RouterInterface $router, CommandBusInterface $commandBus, FormFactoryInterface $formFactory, FlashBagInterface $flashBag) {
        $this->engine = $engine;
        $this->router = $router;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Request $request
     * @param Institution $institution
     * @return Response
     */
    public function __invoke(Request $request, Institution $institution): Response {
        $update = new Update($institution);

        $form = $this->formFactory->create(UpdateType::class, $update, ['submit' => true]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($update);
            $this->flashBag->add('success', 'flash.admin.institution.update.success');

            return new RedirectResponse($this->router->generate('admin_institution_list'));
        }

        return $this->engine->renderResponse('Admin/Institution/update.html.twig',
            [
                'institution' => $institution,
                'form' => $form->createView()
            ]);
    }


}