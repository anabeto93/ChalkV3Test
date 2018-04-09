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
use App\Domain\Exception\Institution\NameAlreadyUsedException;
use App\Domain\Model\Institution;
use App\Ui\Admin\Form\Type\Institution\UpdateType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UpdateAction {
    const TEMPLATE = 'Admin/Institution/update.html.twig';
    const ROUTE_REDIRECT_AFTER_SUCCESS = 'admin_institution_list';
    const TRANS_VALIDATOR_NAME_USED = 'validator.name.alreadyUsed';

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
     * UpdateAction constructor.
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
     * @return Response
     */
    public function __invoke(Request $request, Institution $institution): Response {
        $update = new Update($institution);

        $form = $this->formFactory->create(UpdateType::class, $update, ['submit' => true]);
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {
            try {
                $this->commandBus->handle($update);
                $this->flashBag->add('success', 'flash.admin.institution.update.success');

                return new RedirectResponse($this->router->generate(self::ROUTE_REDIRECT_AFTER_SUCCESS));
            } catch (NameAlreadyUsedException $exception) {
                $form->get('name')->addError(new FormError(
                    $this->translator->trans(
                        self::TRANS_VALIDATOR_NAME_USED, [], 'validators'
                    )
                ));
            }
        }

        return $this->engine->renderResponse(self::TEMPLATE,
            [
                'institution' => $institution,
                'form' => $form->createView()
            ]);
    }


}