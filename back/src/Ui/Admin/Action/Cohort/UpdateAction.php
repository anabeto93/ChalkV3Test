<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 15:47
 */

namespace App\Ui\Admin\Action\Cohort;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\Cohort\Update;
use App\Domain\Exception\Cohort\TitleAlreadyUsedException;
use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;
use App\Ui\Admin\Form\Type\Cohort\UpdateType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UpdateAction {
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
     * @param Cohort $cohort
     * @return Response|RedirectResponse
     */
    public function __invoke(Request $request, Institution $institution, Cohort $cohort): Response {
        if($cohort->getInstitution() !== $institution) {
            throw new NotFoundHttpException(
                sprintf('The cohort %s does not exist within this institution: %s',
                    $cohort->getTitle(), $institution->getName())
            );
        }

        $update = new Update($institution, $cohort);

        $form = $this->formFactory->create(UpdateType::class, $update, [
            'submit' => true
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle($update);

                $this->flashBag->add('success', 'flash.admin.cohort.update.success');

                return new RedirectResponse($this->router->generate(
                    'admin_cohort_list', ['institution' => $institution->getId()]
                ));
            } catch(TitleAlreadyUsedException $exception) {
                $form->get('title')->addError(new FormError(
                    $this->translator->trans('validator.title.alreadyUsed', [], 'validators')
                ));
            }
        }


        return $this->engine->renderResponse('Admin/Cohort/update.html.twig',
            [
                'institution' => $institution,
                'form' => $form->createView()
            ]
        );
    }
}