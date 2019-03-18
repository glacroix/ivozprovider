<?php

namespace DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ivoz\Provider\Domain\Model\WebPortal\WebPortal;
use Ivoz\Provider\Domain\Model\WebPortal\Logo;

class ProviderWebPortal extends Fixture implements DependentFixtureInterface
{
    use \DataFixtures\FixtureHelperTrait;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->disableLifecycleEvents($manager);
        $manager->getClassMetadata(WebPortal::class)->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $item1 = $this->createEntityInstance(WebPortal::class);
        (function () {
            $this->setUrl("https://example.com");
            $this->setKlearTheme("redmond");
            $this->setUrlType("god");
            $this->setName("Platform Administration Portal");
            $this->setUserTheme("default");
            $this->setLogo(new Logo(null, null, null));
        })->call($item1);

        $this->addReference('_reference_ProviderWebPortal1', $item1);
        $this->sanitizeEntityValues($item1);
        $manager->persist($item1);

        $item2 = $this->createEntityInstance(WebPortal::class);
        (function () {
            $this->setUrl("https://test-ivozprovider.irontec.com");
            $this->setKlearTheme("irontec-red");
            $this->setUrlType("brand");
            $this->setName("Irontec Ivozprovider God Portal");
            $this->setUserTheme("default");
            $this->setLogo(new Logo(null, null, null));
        })->call($item2);

        $item2->setBrand($this->getReference('_reference_ProviderBrand1'));
        $this->addReference('_reference_ProviderWebPortal2', $item2);
        $this->sanitizeEntityValues($item2);
        $manager->persist($item2);

        $item3 = $this->createEntityInstance(WebPortal::class);
        (function () {
            $this->setUrl("https://users.artemis.irontec.com");
            $this->setKlearTheme("redmond");
            $this->setUrlType("user");
            $this->setName("Users");
            $this->setUserTheme("default");
            $this->setLogo(new Logo(null, null, null));
        })->call($item3);

        $item3->setBrand($this->getReference('_reference_ProviderBrand1'));
        $this->addReference('_reference_ProviderWebPortal3', $item3);
        $this->sanitizeEntityValues($item3);
        $manager->persist($item3);

    
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ProviderBrand::class
        );
    }
}
