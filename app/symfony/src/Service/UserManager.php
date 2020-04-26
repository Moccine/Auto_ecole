<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserManager
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var TokenGeneratorInterface */
    private $tokenGenerator;
    private $userRepository;

    public function __construct(EntityManagerInterface $em, TokenGeneratorInterface $tokenGenerator)
    {
        $this->em = $em;
        $this->tokenGenerator = $tokenGenerator;
        $this->userRepository = $this->em->getRepository(User::class);
    }

    /**
     * Creates an empty user instance.
     */
    public function createUser()
    {
        return new User();
    }

    /**
     * Deletes a user.
     * @param User $user
     */
    public function deleteUser(User $user)
    {
    }

    /**
     * Finds one user by the given criteria.
     * @param array $criteria
     * @return array
     */
    public function findUserBy(array $criteria): array
    {
        return $this->userRepository->findBy($criteria);
    }

    /**
     * Find a user by its username.
     *
     * @param string $username
     *
     * @return User|null
     */
    public function findUserByUsername($username): ?User
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['username' => $username]);

        return $user;
    }

    /**
     * Finds a user by its email.
     *
     * @param string $email
     *
     * @return User|null
     */
    public function findUserByEmail($email): ?User
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $email]);

        return $user;
    }

    /**
     * Finds a user by its username or email.
     *
     * @param string $usernameOrEmail
     *
     * @return User|null
     */
    public function findUserByUsernameOrEmail($usernameOrEmail): ?User
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['username' => $usernameOrEmail]);

        return $user;
    }

    /**
     * Finds a user by its confirmationToken.
     *
     * @param string $token
     *
     * @return User|null
     */
    public function findUserByConfirmationToken($token): ?User
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            'confirmationToken' => $token
        ]);
        return $user;
    }

    /**
     * Returns a collection with all user instances.
     *
     * @return array|object[]
     */
    public function findUsers(){
        return $this->userRepository->findAll();
    }

    /**
     * Returns the user's fully qualified class name.
     *
     * @return string
     */
    public function getClass()
    {
    }

    public function reloadUser(User $user): ?User
    {
    }

    /**
     * Updates a user.
     *
     * @param User $user
     * @return User|null
     */
    public function updateUser(User $user): ?User
    {
        $this->em->flush();
    }

    /**
     * Updates the canonical username and email fields for a user.
     *
     * @param User $user
     * @return User|null
     */
    public function updateCanonicalFields(User $user): ?User
    {
    }

    /**
     * Updates a user password if a plain password is set.
     *
     * @param User $user
     * @return User|null
     */
    public function updatePassword(User $user): ?User
    {
    }

    public function generateToken()
    {
        return $this->tokenGenerator->generateToken();
    }

}