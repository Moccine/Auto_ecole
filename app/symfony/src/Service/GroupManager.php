<?php


namespace App\Service;



use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class GroupManager
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
     * Returns an empty group instance.
     *
     * @param string $name
     *
     * @return GroupManager
     */
    public function createGroup($name)
    {
        // TODO: Implement createGroup() method.
    }

    /**
     * Deletes a group.
     *
     * @param  $group
     */
    public function deleteGroup($group)
    {
        // TODO: Implement deleteGroup() method.
    }

    /**
     * Finds one group by the given criteria.
     *
     * @param array $criteria
     *
     * @return GroupManager

     */
    public function findGroupBy(array $criteria)
    {
        // TODO: Implement findGroupBy() method.
    }

    /**
     * Finds a group by name.
     *
     * @param string $name
     *
     * @return GroupManager
     */
    public function findGroupByName($name)
    {
        // TODO: Implement findGroupByName() method.
    }

    /**
     * Returns a collection with all group instances.
     *
     * @return \Traversable
     */
    public function findGroups()
    {
        // TODO: Implement findGroups() method.
    }

    /**
     * Returns the group's fully qualified class name.
     *
     * @return string
     */
    public function getClass()
    {
        // TODO: Implement getClass() method.
    }

    public function updateGroup()
    {
        // TODO: Implement updateGroup() method.
    }
}