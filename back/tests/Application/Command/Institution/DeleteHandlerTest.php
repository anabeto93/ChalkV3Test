<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 04/04/2018
 * Time: 14:12
 */

namespace Tests\Application\Command\Institution;


use App\Application\Command\Institution\Delete;
use App\Application\Command\Institution\DeleteHandler;
use App\Domain\Model\Institution;
use App\Domain\Repository\InstitutionRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeleteHandlerTest extends TestCase {
    public function testHandle() {
        $institution = $this->prophesize(Institution::class);

        //Mock
        $institutionRepository = $this->prophesize(InstitutionRepositoryInterface::class);
        $institutionRepository->remove($institution->reveal())->shouldBeCalled();

        //Handler
        $handler = new DeleteHandler($institutionRepository->reveal());
        $handler->handle(new Delete($institution->reveal()));
    }
}