<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 04/04/2018
 * Time: 10:52
 */

namespace Tests\Application\Command\Institution;


use App\Application\Command\Institution\Create;
use App\Application\Command\Institution\CreateHandler;
use App\Domain\Exception\Institution\NameAlreadyUsedException;
use App\Domain\Model\Institution;
use App\Domain\Repository\InstitutionRepositoryInterface;
use App\Domain\Uuid\Generator;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class CreateHandlerTest extends TestCase {
    /** @var ObjectProphecy */
    private $institutionRepository;

    /** @var ObjectProphecy */
    private $generator;

    /** @var ObjectProphecy */
    private $dateTime;

    public function setUp() {
        $this->institutionRepository = $this->prophesize(InstitutionRepositoryInterface::class);
        $this->generator = $this->prophesize(Generator::class);
        $this->dateTime = new \DateTime();
    }

    public function testNameAlreadyUsed() {
        //Expected
        $this->setExpectedException(NameAlreadyUsedException::class);

        //Context
        $institution = $this->prophesize(Institution::class);
        $command = new Create();
        $command->name = 'name';

        //Mock
        $this->institutionRepository->findByName('name')->shouldBeCalled()->willReturn
        ($institution->reveal());

        //Handler
        $handler = new CreateHandler(
            $this->institutionRepository->reveal(),
            $this->generator->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }

    public function testHandle() {
        //Expected
        $expected = new Institution(
            'institution-uuid',
            'name',
            $this->dateTime
        );

        //Context
        $command = new Create();
        $command->name = 'name';

        //Mock
        $this->institutionRepository->findByName('name')->shouldBeCalled()->willReturn(null);
        $this->institutionRepository->add($expected)->shouldBeCalled();
        $this->generator->generateUuid()->shouldBeCalled()->willReturn('institution-uuid');

        //Handler
        $handler = new CreateHandler(
            $this->institutionRepository->reveal(),
            $this->generator->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }
}