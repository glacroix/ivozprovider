<?php

namespace Tests\Provider\TerminalModel;

use Ivoz\Provider\Domain\Model\TerminalModel\TerminalModel;
use Ivoz\Provider\Domain\Model\TerminalModel\TerminalModelDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\DbIntegrationTestHelperTrait;

class TerminalModelLifeCycleTest extends KernelTestCase
{
    use DbIntegrationTestHelperTrait;

    /**
     * @return TerminalModelDto
     */
    protected function getTerminalModelDto()
    {
        $terminalModelDto = new TerminalModelDto();
        $terminalModelDto
            ->setIden('TestModel')
            ->setName('Generic TestModel')
            ->setDescription('Test SIP Model')
            ->setGenericTemplate('')
            ->setSpecificTemplate('')
            ->setGenericUrlPattern('')
            ->setSpecificUrlPattern('')
            ->setTerminalManufacturerId(1);

        return $terminalModelDto;
    }

    protected function addTerminalModel()
    {
        $dto = $this->getTerminalModelDto();
        return $this
            ->entityTools
            ->persistDto($dto, null, true);
    }

    protected function updateTerminalModel()
    {
        $terminalModelRepository = $this->em
            ->getRepository(TerminalModel::class);

        $terminalModel = $terminalModelRepository->find(1);

        /** @var TerminalDto $terminalModelDto */
        $terminalModelDto = $this->entityTools->entityToDto(
            $terminalModel
        );

        $terminalModelDto
            ->setName('UpdatedName');

        return $this
            ->entityTools
            ->persistDto($terminalModelDto, $terminalModel, true);
    }


    protected function removeTerminalModel()
    {
        $terminalModelRepository = $this->em
            ->getRepository(TerminalModel::class);

        $terminalModel = $terminalModelRepository->find(1);

        return $this
            ->entityTools
            ->remove($terminalModel);
    }

    /**
     * @test
     */
    public function it_persists_routing_tags()
    {
        $terminalModelRepository = $this->em
            ->getRepository(TerminalModel::class);

        $fixtureTerminalModel = $terminalModelRepository->findAll();

        $this->addTerminalModel();

        $terminalModels = $terminalModelRepository->findAll();
        $this->assertCount(
            count($fixtureTerminalModel) + 1,
            $terminalModels
        );
    }

    /**
     * @test
     */
    public function it_triggers_lifecycle_services()
    {
        $this->addTerminalModel();
        $this->assetChangedEntities([
            TerminalModel::class
        ]);
    }

    /**
     * @test
     */
    public function it_triggers_update_lifecycle_services()
    {
        $this->updateTerminalModel();
        $this->assetChangedEntities([
            TerminalModel::class
        ]);
    }

    /**
     * @test
     */
    public function it_triggers_remove_lifecycle_services()
    {
        $this->removeTerminalModel();
        $this->assetChangedEntities([
            TerminalModel::class
        ]);
    }
}
