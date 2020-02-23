<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FranceLocationRepository")
 */
class FranceLocation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $regionCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $circonscription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $chefLieu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $regionName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $departmentNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $departmentName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prefecture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $communeName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $circonscriptionNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $postaCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codeInsee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $longitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegionCode(): ?int
    {
        return $this->regionCode;
    }

    public function setRegionCode(int $regionCode): self
    {
        $this->regionCode = $regionCode;

        return $this;
    }

    public function getCirconscription(): ?string
    {
        return $this->circonscription;
    }

    public function setCirconscription(string $circonscription): self
    {
        $this->circonscription = $circonscription;

        return $this;
    }

    public function getChefLieu(): ?string
    {
        return $this->chefLieu;
    }

    public function setChefLieu(string $chefLieu): self
    {
        $this->chefLieu = $chefLieu;

        return $this;
    }

    public function getRegionName(): ?string
    {
        return $this->regionName;
    }

    public function setRegionName(string $regionName): self
    {
        $this->regionName = $regionName;

        return $this;
    }

    public function getDepartmentNumber(): ?string
    {
        return $this->departmentNumber;
    }

    public function setDepartmentNumber(string $departmentNumber): self
    {
        $this->departmentNumber = $departmentNumber;

        return $this;
    }

    public function getDepartmentName(): ?string
    {
        return $this->departmentName;
    }

    public function setDepartmentName(string $departmentName): self
    {
        $this->departmentName = $departmentName;

        return $this;
    }

    public function getPrefecture(): ?string
    {
        return $this->prefecture;
    }

    public function setPrefecture(string $prefecture): self
    {
        $this->prefecture = $prefecture;

        return $this;
    }

    public function getCommuneName(): ?string
    {
        return $this->communeName;
    }

    public function setCommuneName(string $communeName): self
    {
        $this->communeName = $communeName;

        return $this;
    }

    public function getCirconscriptionNumber(): ?string
    {
        return $this->circonscriptionNumber;
    }

    public function setCirconscriptionNumber(string $circonscriptionNumber): self
    {
        $this->circonscriptionNumber = $circonscriptionNumber;

        return $this;
    }

    public function getPostaCode(): ?string
    {
        return $this->postaCode;
    }

    public function setPostaCode(string $postaCode): self
    {
        $this->postaCode = $postaCode;

        return $this;
    }

    public function getCodeInsee(): ?string
    {
        return $this->codeInsee;
    }

    public function setCodeInsee(string $codeInsee): self
    {
        $this->codeInsee = $codeInsee;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
