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
use App\Domain\Exception\Cohort\TitleAlreadyUsedException;
use App\Domain\Model\Institution;
use App\Ui\Admin\Form\Type\Cohort\CreateType;
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
    public function __construct(EngineInterface $engine, CommandBusInterface $commandBus, FormFactoryInterface $formFactory, FlashBagInterface $flashBag, RouterInterface $router, TranslatorInterface $translator) {
        $this->engine = $engine;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
        $this->router = $router;
        $this->translator = $translator;
    }


    /**
     * @param Request $request
     * @param Institution $institution
     * @return Response|RedirectResponse
     */
    public function __invoke(Request $request, Institution $institution): Response {
        $create = new Create($institution);

        $form = $this->formFactory->create(CreateType::class, $create, [
            'submit' => true,
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle($create);

                $this->flashBag->add('success', 'flash.admin.cohort.create.success');

                return new RedirectResponse($this->router->generate(
                    'admin_cohort_list', ['institution' => $institution->getId()]
                ));
            } catch(TitleAlreadyUsedException $exception) {
                $form->get('title')->addError(new FormError(
                   $this->translator->trans('validator.title.alreadyUsed', [], 'validators')
                ));
            }
        }

        return $this->engine->renderResponse('Admin/Cohort/create.html.twig', [
            'institution' => $institution,
            'form' => $form->createView()
        ]);
    }
}