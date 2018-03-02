<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 02/03/2018
 * Time: 17:43
 */

namespace App\Ui\Admin\Action\Institution\UserInstitution;


use App\Application\Adapter\QueryBusInterface;
use App\Application\Query\Institution\UserInstitution\UserInstitutionListQuery;
use App\Domain\Model\Institution;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ListAction {
    /** @var EngineInterface */
    private $engine;

    /** @var QueryBusInterface */
    private $queryBus;

    /**
     * ListAction constructor.
     * @param EngineInterface $engine
     * @param QueryBusInterface $queryBus
     */
    public function __construct(EngineInterface $engine, QueryBusInterface $queryBus) {
        $this->engine = $engine;
        $this->queryBus = $queryBus;
    }

    /**
     * @param Institution $institution
     * @return Response
     */
    public function __invoke(Institution $institution): Response {
        $userList = $this->queryBus->handle(new UserInstitutionListQuery($institution));

        return $this->engine->renderResponse('Admin/Institution/UserInstitution/list.html.twig',
            [
                'userList' => $userList,
                'institution' => $institution
            ]);
    }
}