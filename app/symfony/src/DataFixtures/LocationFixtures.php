<?php


namespace App\DataFixtures;

use App\Entity\Location;
use App\Entity\MettingPoint;
use App\Service\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\Address;

class LocationFixtures extends Fixture
{
    public static function getGroups(): array
    {
        return ['location'];
    }

    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i <= 30; $i++) {
            $location = new Location();
            $location->setAddress($faker->address)
                ->setCity(Address::citySuffix())
                ->setPostalCode(Address::postcode())
                ->setActivated(rand(0, 1))
                ->setCountry($faker->country);
            $manager->persist($location);
        }


        for ($i = 0; $i <= 5; $i++) {
            $mettingPoint = new MettingPoint();
            $mettingPoint->setAddress($faker->streetAddress)
                ->setCity($faker->city)
                ->setPostalCode(Address::postcode())
                ->setActivated(rand(0, 1))
                ->setCountry($faker->country);
            $manager->persist($mettingPoint);
            $manager->flush();
            $this->addReference('mettingPoint'.$i, $mettingPoint);
        }
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
