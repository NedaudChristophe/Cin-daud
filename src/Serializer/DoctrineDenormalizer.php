<?php

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DoctrineDenormalizer implements DenormalizerInterface
{

    /**
    * Instance de EntityManagerInterface
    *
    * @var EntityManagerInterface
    */
    private $entityManagerInterface;
    
    /**
    * Constructor
    */
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }
    /**
     * Appel quand on a besoin de denormaliser
     *
     * @param mixed $data 
     * @param string $type
     * @param string|null $format
     */
    public function supportsDenormalization($data, string $type, ?string $format = null)
    {
        //? je sais traiter le cas où $data est un ID
        //? je sais traiter le cas où $type est un entity
        $dataIsID = is_numeric($data);
        
        $typeIsEntity = strpos($type, 'App\Entity') === 0; // ma chaine commence par App\Entity

        
        return $typeIsEntity && $dataIsID;
    }

    /**
     * @param mixed $data 
     * @param string $type 
     * @param string|null $format
     * @param array $context
     */
    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        
        $denormalizedEntity = $this->entityManagerInterface->find($type, $data);
        
        return $denormalizedEntity;
    }
}