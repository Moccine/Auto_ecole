<?php
namespace App\DataFixtures;

use App\Entity\MettingPoint;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\Address;
use FOS\UserBundle\Model\UserManagerInterface;

class userFixtures extends Fixture
{
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $index =sizeof(User::ERP_USER_ROLES)-1;


        for ($i=0; $i<=100; $i++) {
            /** @var User $user */
            $user = $this->userManager->createUser();
            $roles = User::ERP_USER_ROLES[rand(0, $index)];
            $user->setUsername($faker->name);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPlainPassword('auto');
            $user->setBirthDate($faker->dateTimeBetween('-30 years'));
            $user->setZipCode(Address::postcode());
            $user->setPhone($faker->phoneNumber);
            $user->setAddress($faker->streetAddress);
            $user->setCity($faker->city);
            $user->setEnabled(true);
            $user->setRoles((array)$roles);
            /** @var MettingPoint $mp */
            if ($roles == User::ROLE_RIVING_INSTRUCTOR) {
                $mp= $manager->merge($this->getReference('mettingPoint'.rand(0, 5)));
                $mp->addUser($user);
            }

            $this->userManager->updateUser($user);

            //$this->addReference($fixture['username'], $user);
        }
    }

    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
