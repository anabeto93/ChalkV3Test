<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 00:43
 */

namespace App\Ui\Admin\Action\Institution;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Institution\Create;
use App\Domain\Exception\Institution\NameAlreadyUsedException;
use App\Ui\Admin\Form\Type\Institution\CreateType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

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

    /** @var TranslatorInterface */
    private $translator;

    /**
     * CreateAction constructor.
     * @param EngineInterface $engine
     * @param CommandBusInterface $commandBus
     * @param FormFactoryInterface $formFactory
     * @param FlashBagInterface $flashBag
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     */
    public function __construct(EngineInterface $engine, CommandBusInterface $commandBus,
                                FormFactoryInterface $formFactory, FlashBagInterface $flashBag,
                                RouterInterface $router, TranslatorInterface $translator) {
        $this->engine = $engine;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function __invoke(Request $request): Response {
        $create = new Create();

        $form = $this->formFactory->create(CreateType::class, $create
        , ['submit' => true]);


        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {
            try {
                $this->commandBus->handle($create);
                $this->flashBag->add('success', 'flash.admin.institution.create.success');

                return new RedirectResponse($this->router->generate('admin_institution_list'));
            } catch(NameAlreadyUsedException $exception) {
                $form->get('name')->addError(new FormError(
                    $this->translator->trans('validator.name.alreadyUsed', [], 'validators')
                ));
            }
        }

        return $this->engine->renderResponse('Admin/Institution/create.html.twig',
            [
                'form' => $form->createView()
            ]);
    }
}