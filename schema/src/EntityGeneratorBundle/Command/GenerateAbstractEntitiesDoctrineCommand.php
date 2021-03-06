<?php

namespace EntityGeneratorBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\GenerateEntitiesDoctrineCommand as ParentCommand;
use EntityGeneratorBundle\Tools\AbstractEntityGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Generate entity classes from mapping information
 * @codeCoverageIgnore
 * @author Mikel Madariaga <mikel@irontec.com>
 */
class GenerateAbstractEntitiesDoctrineCommand extends ParentCommand
{
    const OUTCOME = 'abstract entities';

    use ExecuteGeneratorTrait;

    public function __construct($name = null)
    {
        $this->injectEmbeddedClasses = true;
        $this->skipEntities = true;
        $this->disconnectedClassMetadata = true;

        return parent::__construct($name);
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('provider:generate:entities:abstract')
            ->setAliases(array('generate:provider:entities:abstract'))
            ->setDescription('Generates entity abstract classes and method stubs from your mapping information')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'A bundle name, a namespace, or a class name'
            )
            ->addOption(
                'path',
                null,
                InputOption::VALUE_REQUIRED,
                'The path where to generate entities when it cannot be guessed',
                'src'
            );
    }

    /**
     * get a ivozprovider entity generator
     *
     * @return EntityGenerator
     */
    protected function getEntityGenerator()
    {
        $entityGenerator = new AbstractEntityGenerator();
        $entityGenerator->setBackupExisting(false);
        $entityGenerator->setGenerateStubMethods(true);
        $entityGenerator->setRegenerateEntityIfExists(true);
        $entityGenerator->setUpdateEntityIfExists(true);
        $entityGenerator->setNumSpaces(4);
        $entityGenerator->setAnnotationPrefix('ORM\\');

        return $entityGenerator;
    }
}
