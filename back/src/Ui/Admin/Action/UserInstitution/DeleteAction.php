<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 05/03/2018
 * Time: 15:12
 */

namespace App\Ui\Admin\Action\UserInstitution;


use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\UserInstitution\Delete;
use App\Domain\Model\Institution;
use App\Domain\Model\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class DeleteAction {
    /** @var RouterInterface */
    private $router;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * DeleteAction constructor.
     * @param RouterInterface $router
     * @param CommandBusInterface $commandBus
     * @param FlashBagInterface $flashBag
     */
    public function __construct(RouterInterface $router, CommandBusInterface $commandBus, FlashBagInterface $flashBag) {
        $this->router = $router;
        $this->commandBus = $commandBus;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Institution $institution
     * @param User $user
     * @return RedirectResponse
     * @throws NotFoundHttpException
     */
    public function __invoke(Institution $institution, User $user): RedirectResponse {
        $userInstitution = $institution->getUserInstitution($user, $institution);
        if(!$userInstitution) {
            throw new NotFoundHttpException('Cannot delete user/institution from one they are not a part of.');
        }

        $this->commandBus->handle(new Delete($userInstitution));

        $this->flashBag->add('success', 'flash.admin.userInstitution.delete.success');

        return new RedirectResponse(
            $this->router->generate('admin_institution_user_list', ['institution' =>
                $institution->getId()])
        );
    }
}