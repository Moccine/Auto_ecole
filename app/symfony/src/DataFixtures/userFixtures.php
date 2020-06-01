<?php

namespace App\DataFixtures;

use App\Entity\MettingPoint;
use App\Entity\User;
use App\Service\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\Address;

class userFixtures extends Fixture
{
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function load(ObjectManager $manager)
    {
        $datas = $this->getDatas();
        foreach ($datas as $data) {
            $user = $this->userManager->createUser();
            $user->setUsername($data['userName']);
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setEmail($data['email']);
            $user->setBirthDate($data['birthDate']);
            $user->setZipCode($data['zipCode']);
            $user->setPhone($data['phone']);
            $user->setAddress($data['adresse']);
            $user->setCity($data['city']);
            $user->setEnabled(true);
            $user->setPlainPassword($data['plainPassword']);
            $user->setRole($data['rôle'])->setConfirmationToken(null);
            $this->userManager->hasPassword($user);
            $manager->persist($user);
            $this->userManager->updateUser($user);
        }

    }

    public function getDatas()
    {
        $faker = Factory::create('fr_FR');
        return [
            [
                'rôle' => User::ROLE_SUPER_ADMIN,
                 'userName' => 'super_admin',
                'firstName' => 'Jerome',
                'lastName' => 'Perroty',
                'email' => 'super_admin@gmail.com',
                'birthDate' => new \DateTime('2004-01-01'),
                'zipCode' => Address::postcode(),
                'phone' => '0770186890',
                'adresse' => $faker->streetAddress,
                'city' => $faker->city,
                'plainPassword' => 'Colombes2020',
            ],
            [
                'rôle' => User::ROLE_DRIVING_STUDENT,
                'userName' => 'test_student',
                'firstName' => 'Maxim',
                'lastName' => 'Middle',
                'email' => 'student_test@gmail.com',
                'birthDate' => new \DateTime('2005-01-01'),
                'zipCode' => Address::postcode(),
                'phone' => '0770186894',
                'adresse' => $faker->streetAddress,
                'city' => $faker->city,
                'plainPassword' => 'Colombes2020',
            ]
        ];

    }

    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
