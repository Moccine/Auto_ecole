<?php

namespace App\Entity;

use App\Entity\Traits\ActivatedTrait;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    use CreatedAtTrait;
    use ActivatedTrait;
    use UpdatedAtTrait;
    /**
     * roles array.
     */
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_COORDINATOR = 'ROLE_COORDINATOR';
    const ROLE_RIVING_INSTRUCTOR = 'ROLE_DRIVING_INSTRUCTOR';
    const ROLE_DRIVING_STUDENT = 'ROLE_DRIVING_STUDENT';


    const ROLES = [
        self::ROLE_SUPER_ADMIN => 'user.role.super_admin',
        self::ROLE_COORDINATOR => 'user.role.coordinator',
        self::ROLE_RIVING_INSTRUCTOR => 'user.role.driving_instructor',
        self::ROLE_DRIVING_STUDENT => 'user.role.driving_student',
    ];

    const USER_ROLES = [
        self::ROLE_SUPER_ADMIN => 'user.role.super_admin',
        self::ROLE_COORDINATOR => 'user.role.coordinator',
        self::ROLE_RIVING_INSTRUCTOR => 'user.role.driving_instructor',
        self::ROLE_DRIVING_STUDENT => 'user.role.driving_student',
    ];

    const ERP_USER_ROLES = [
        self::ROLE_SUPER_ADMIN,
        self::ROLE_ADMIN,
        self::ROLE_COORDINATOR,
        self::ROLE_RIVING_INSTRUCTOR,
        self::ROLE_DRIVING_STUDENT
    ];
    const DESACTIVATED =0 ;
    const ACTIVATED = 1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", nullable=true)
     */
    private $registrationNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $extraAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $qualification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $photo;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="users_images", fileNameProperty="photo")
     */
    private $photoFile;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $photoSize;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TimeSlot", mappedBy="user")
     */
    private $timeSlot;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Location", mappedBy="user")
     */
    private $Location;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MettingPoint", inversedBy="users")
     */
    private $mettingPoints;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Orders", mappedBy="student")
     */
    private $orders;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->registrationNumber = (substr(md5(uniqid(rand(), true)), 0, 6));
        $this->timeSlot = new ArrayCollection();
        $this->Location = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->activated = false;
        $this->cards = new ArrayCollection();
        $this->mettingPoints = new ArrayCollection();
        $this->orders = new ArrayCollection();

        // your own logic
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param $address
     * @return User
     */
    public function setAddress($address): User
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     * @return $this
     */
    public function setBirthDate(\DateTime $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    /**
     * @param int $zipCode
     * @return $this
     */
    public function setZipCode(?int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email): User
    {
        $this->setUsername($email);
        $this->email = parent::setEmail($email);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     */
    public function setBirthday(\DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getExtraAddress(): string
    {
        return $this->extraAddress;
    }

    /**
     * @param string $extraAddress
     */
    public function setExtraAddress(string $extraAddress): void
    {
        $this->extraAddress = $extraAddress;
    }

    /**
     * @return string
     */
    public function getRegistrationNumber(): string
    {
        return $this->registrationNumber;
    }

    /**
     * @param string $registrationNumber
     */
    public function setRegistrationNumber(string $registrationNumber): void
    {
        $this->registrationNumber = $registrationNumber;
    }

    /**
     * @return string
     */
    public function getQualification(): ?string
    {
        return $this->qualification;
    }

    /**
     * @param string $qualification
     */
    public function setQualification(string $qualification): void
    {
        $this->qualification = $qualification;
    }

    /**
     * @return string
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     * @return $this
     */
    public function setPhoto(?string $photo): self
    {
        dump($photo);
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return int
     */
    public function getPhotoSize(): ?int
    {
        return $this->photoSize;
    }

    /**
     * @param int $photoSize
     */
    public function setPhotoSize(int $photoSize): void
    {
        $this->photoSize = $photoSize;
    }

    /**
     * Get photoFile.
     *
     * @return null|File
     */
    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    /**
     * Set photo.
     *
     * @param File|UploadedFile $photoFile
     *
     * @return User
     */
    public function setPhotoFile(?File $photoFile = null): self
    {
        $this->photoFile = $photoFile;

        if (null !== $photoFile) {
            $this->photoSize = $photoFile->getSize();
        }

        return $this;
    }

    /**
     * @param string $role
     *
     * @throws \Exception
     */
    public function setRole($role)
    {
        if (!in_array($role, array_keys(self::ROLES))) {
            throw new \Exception('Invalid role: [' . $role . ']');
        }

        if ($this->hasRole($role)) {
            return;
        }

        $this->setRoles([$role]);
    }

    /**
     * Get roles.
     *
     * @return array
     */
    public function getRoles(): array
    {
        return (array)$this->roles;
    }

    /**
     * @return string
     */
    public function getRoleTranslationKey()
    {
        return self::ROLES[$this->getRole()];
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getRole()
    {
        if ($this->hasRole(self::ROLE_SUPER_ADMIN)) {
            return self::ROLE_SUPER_ADMIN;
        } elseif ($this->hasRole(self::ROLE_ADMIN)) {
            return self::ROLE_ADMIN;
        } elseif ($this->hasRole(self::ROLE_COORDINATOR)) {
            return self::ROLE_COORDINATOR;
        } elseif ($this->hasRole(self::ROLE_RIVING_INSTRUCTOR)) {
            return self::ROLE_RIVING_INSTRUCTOR;
        } else {
            throw new \Exception("We can't find any expected role from the user, id: [" . $this->id . '].');
        }
    }

    /**
     * @return Collection|TimeSlot[]
     */
    public function getTimeSlot(): Collection
    {
        return $this->timeSlot;
    }

    /**
     * @param TimeSlot $timeSlot
     * @return $this
     */
    public function addTimeSlot(TimeSlot $timeSlot): self
    {
        if (!$this->timeSlot->contains($timeSlot)) {
            $this->timeSlot[] = $timeSlot;
            $timeSlot->setUser($this);
        }

        return $this;
    }

    /**
     * @param TimeSlot $timeSlot
     * @return $this
     */
    public function removeTimeSlot(TimeSlot $timeSlot): self
    {
        if ($this->timeSlot->contains($timeSlot)) {
            $this->timeSlot->removeElement($timeSlot);
            // set the owning side to null (unless already changed)
            if ($timeSlot->getUser() === $this) {
                $timeSlot->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Location[]
     */
    public function getLocation(): Collection
    {
        return $this->Location;
    }

    /**
     * @param Location $location
     * @return $this
     */
    public function addLocation(Location $location): self
    {
        if (!$this->Location->contains($location)) {
            $this->Location[] = $location;
            $location->setUser($this);
        }

        return $this;
    }

    /**
     * @param Location $location
     * @return $this
     */
    public function removeLocation(Location $location): self
    {
        if ($this->Location->contains($location)) {
            $this->Location->removeElement($location);
            // set the owning side to null (unless already changed)
            if ($location->getUser() === $this) {
                $location->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MettingPoint[]
     */
    public function getMettingPoints(): Collection
    {
        return $this->mettingPoints;
    }

    /**
     * @param MettingPoint $mettingPoint
     * @return $this
     */
    public function addMettingPoint(MettingPoint $mettingPoint): self
    {
        if (!$this->mettingPoints->contains($mettingPoint)) {
            $this->mettingPoints[] = $mettingPoint;
        }

        return $this;
    }

    /**
     * @param MettingPoint $mettingPoint
     * @return $this
     */
    public function removeMettingPoint(MettingPoint $mettingPoint): self
    {
        if ($this->mettingPoints->contains($mettingPoint)) {
            $this->mettingPoints->removeElement($mettingPoint);
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setStudent($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getStudent() === $this) {
                $order->setStudent(null);
            }
        }

        return $this;
    }
}
