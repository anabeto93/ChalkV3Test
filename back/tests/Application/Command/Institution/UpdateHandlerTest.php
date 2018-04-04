<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 04/04/2018
 * Time: 11:47
 */

namespace Tests\Application\Command\Institution;


use App\Application\Command\Institution\Update;
use App\Application\Command\Institution\UpdateHandler;
use App\Domain\Exception\Institution\NameAlreadyUsedException;
use App\Domain\Model\Institution;
use App\Domain\Repository\InstitutionRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class UpdateHandlerTest extends TestCase {
    /** @var ObjectProphecy */
    private $institutionRepository;

    /** @var \DateTime */
    private $dateTime;

    public function setUp() {
        $this->institutionRepository = $this->prophesize(InstitutionRepositoryInterface::class);
        $this->dateTime = new \DateTime();
    }

    public function testNameAlreadyUsed() {
        //Expected
        $this->setExpectedException(NameAlreadyUsedException::class);

        //Context
        $institutionWithSameName = $this->prophesize(Institution::class);
        $institution = new Institution(
            'uuid-new',
            'name',
            $this->dateTime
        );

        $command = new Update($institution);
        $command->name = 'Chalkboard';

        //Mock
        $this->institutionRepository->findByName('Chalkboard')->shouldBeCalled()->willReturn
        ($institutionWithSameName->reveal());

        //Handler
        $handler = new UpdateHandler(
            $this->institutionRepository->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }

    public function testHandle() {
        //Expected
        $expected = new Institution(
            'uuid-new',
            'Chalkboard',
            new \DateTime('2018-04-04 12:00:00.000')
        );

        $expected->update(
            'Chalkboard Education',
            $this->dateTime,
            0
        );

        //Context
        $institution = new Institution(
            'uuid-new',
            'Some name',
            new \DateTime('2018-04-04 12:00:00.000')
        );

        $command = new Update($institution);
        $command->name = 'Chalkboard Education';

        //Mock
        $this->institutionRepository->findByName('Chalkboard Education')->shouldBeCalled()
            ->willReturn(null);
        $this->institutionRepository->set($expected)->shouldBeCalled();

        //Handler
        $handler = new UpdateHandler(
            $this->institutionRepository->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }
}