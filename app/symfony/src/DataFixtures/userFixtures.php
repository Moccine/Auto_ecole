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
        $faker = Factory::create('fr_FR');
            $user = $this->userManager->createUser();
            $role = User::ROLE_SUPER_ADMIN;
            $user->setUsername('super_admin');
            $user->setFirstName('Jerome');
            $user->setLastName('Perroty');
            $user->setEmail('super_admin@gmail.com');
            $user->setBirthDate(new \DateTime('2004-01-01'));
            $user->setZipCode(Address::postcode());
            $user->setPhone('0770186890');
            $user->setAddress($faker->streetAddress);
            $user->setCity('Paris');
            $user->setEnabled(true);
            $user->setPlainPassword('colombes');
            $user->setRole($role)->setConfirmationToken(null);
            $this->userManager->hasPassword($user);
            $manager->persist($user);
            $this->userManager->updateUser($user);
    }

    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
