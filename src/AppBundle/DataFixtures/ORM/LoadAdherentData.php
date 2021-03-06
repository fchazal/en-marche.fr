<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Membership\AdherentFactory;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LoadAdherentData implements FixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function load(ObjectManager $manager)
    {
        $factory = $this->getAdherentFactory();

        $adherent1 = $factory->createFromArray([
            'password' => 'secret!12345',
            'email' => 'michelle.dufour@example.ch',
            'gender' => 'female',
            'first_name' => 'Michelle',
            'last_name' => 'Dufour',
            'country' => 'CH',
            'birthdate' => '1972-11-23',
        ]);

        $adherent2 = $factory->createFromArray([
            'password' => 'secret!12345',
            'email' => 'carl999@example.fr',
            'gender' => 'male',
            'first_name' => 'Carl',
            'last_name' => 'Mirabeau',
            'country' => 'FR',
            'address' => '122 rue de Mouxy',
            'city' => '73100-73182',
            'postal_code' => '73100',
            'birthdate' => '1950-07-08',
            'position' => 'retired',
        ]);

        $manager->persist($adherent1);
        $manager->persist($adherent2);
        $manager->flush();
    }

    private function getAdherentFactory(): AdherentFactory
    {
        return $this->container->get('app.membership.adherent_factory');
    }
}
