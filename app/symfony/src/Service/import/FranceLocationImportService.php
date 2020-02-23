<?php


namespace App\Service\import;


use App\Entity\Domain;
use App\Entity\FranceLocation;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class FranceLocationImportService extends BaseImportService
{
    protected $filename;

    protected $em;

    protected $delimiter;

    protected $mapping;

    public function __construct($filename, EntityManagerInterface $em, LoggerInterface $logger, $delimiter = ';')
    {
        parent::__construct($filename, $em, $logger, $delimiter);
        $this->mapping = [
            'EU_circo' => 'setCirconscription',
            'code_région' => 'setRegionCode',
            'chef-lieu_région' => 'setChefLieu',
            'numéro_département' => 'setDepartmentNumber',
            'nom_département' => 'setDepartmentName',
            'préfecture' => 'setPrefecture',
            'nom_région' => 'setRegionName',
            'numéro_circonscription' => 'setCirconscriptionNumber',
            'nom_commune' => 'setCommuneName',
            'codes_postaux' => 'setPostaCode',
            'code_insee' => 'setCodeInsee',
            'latitude' => 'setLatitude',
            'longitude' => 'setLongitude',
        ];
    }

    public function importData($folderPath, $header, array $interventions, $skipUpdate)
    {
        $importType = 'update';
        $data = [];
        foreach ($header as $key => $columnName) {
            $data[$columnName] = trim($interventions[$key]);
        }
        $location = $this->em->getRepository(FranceLocation::class)->findOneBy([
            'communeName' => $data['nom_commune']
        ]);

        if (!$location instanceof FranceLocation) {
            $importType = 'insert';
            $location = new FranceLocation();
            $this->em->persist($location);
        }

        // Simple fields
        $this->mapSimpleFields($location, $data);
        return $importType;
    }
}