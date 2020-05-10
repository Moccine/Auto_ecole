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
        $datas = $this->getDatas();
       foreach ($datas as $data) {
            $mettingPoint = new MettingPoint();
            $mettingPoint->setAddress($data['address'])
                ->setCity($data['city'])
                ->setPostalCode($data['postalCode'])
                ->setActivated(true)
                ->setCountry('France');
            $manager->persist($mettingPoint);
            $manager->flush();
        }
    }

    /**
     * @return \string[][]
     */
    public function getDatas()
    {
        return [
            [
                'city' => 'Paris',
                'postalCode' => '75018',
                'address' => '72 boulevard Ney, porte de la chapelle'
            ],
            [
                'city' => 'Paris',
                'postalCode' => '75010',
                'address' => 'Adresse : 18 Rue de Dunkerque (Gare du Nord)'
            ],
            [
                'city' => 'Paris',
                'postalCode' => '75571',
                'address' => 'Place Louis-Armand (Gare de lyon)'
            ]
        ];
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
