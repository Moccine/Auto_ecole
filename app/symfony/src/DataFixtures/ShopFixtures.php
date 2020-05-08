<?php


namespace App\DataFixtures;

use App\Entity\Location;
use App\Entity\Shop;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\Address;

class ShopFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $datas = $this->getDatas();
        foreach ($datas as $data) {
            $hop = new Shop();
            $hop->setName($data['name'])
                ->setCourseNumber($data['course_number'])
                ->setHour($data['hour'])
                ->setPriority($data['priority'])
                ->setPrice($data['price'])
                ->setDescription($data['description']);
            $manager->persist($hop);
        }
        $manager->flush();
    }

    public function getDatas()
    {
        return [
            [
                'name' => 'Le Code de la route',
                'hour' => 0,
                'price' => 29.9,
                'priority' => Shop::BEST_OFFERS,
                'description' => 'Accès illimité pendant 1 an - 2000 questions d’entraînement - Cours en ligne inclus - Conseils personnalisés 7j/7 ',
                'course_number' => 5
            ],
            [
                'name' => '20h de conduite',
                'hour' => 20,
                'price' => 649,
                'priority' => Shop::DRIVING_CARD,
                'description' => ' 6% d\'économie - Facilités de paiement disponibles ',
                'course_number' => 5

            ],
            [
                'name' => '11h de conduite',
                'hour' => 11,
                'price' => 385,
                'priority' => Shop::DRIVING_CARD,
                'description' => '1 h obligatoire - 3% d\'économie',
                'course_number' => 5

            ],
            [
                'name' => '5h de conduite',
                'hour' => 5,
                'price' => 195,
                'priority' => Shop::DRIVING_CARD,
                'description' => 'Accès illimité pendant 1 an - 2000 questions d’entraînement - Cours en ligne inclus - Conseils personnalisés 7j/7 ',
                'course_number' => 5

            ],
            [
                'name' => 'Code + 1h d\'évaluation',
                'hour' => 5,
                'price' => 54.9,
                'priority' => Shop::BEST_OFFERS,
                'description' => 'Accès illimité pendant 1 an - 2000 questions d’entraînement - Cours en ligne inclus - Conseils personnalisés 7j/7 ',
                'course_number' => 5
            ],
            [
                'name' => '1h d\'évaluation',
                'hour' => 1,
                'price' => 34.9,
                'priority' => Shop::BEST_OFFERS,
                'description' => '1h d\'evaluation',
                'course_number' => 5
            ],
        ];
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
