<?php

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DoctrineDenormalizer implements DenormalizerInterface
{
    /**
     * EntityManager
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
    * Constructor
    */
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }

    /**
     * est ce que je sais faire cette denormalisation ?
     *
     * @param mixed $data : ID de l'entité
     * @param string $type : le type de la classe de l'entité (FQCN)
     * @param string|null $format
     */
    public function supportsDenormalization($data, string $type, ?string $format = null): bool
    {

        $isEntity = strpos($type, "App\Entity") === 0;

        //? je vérifie que $data est un ID, un entier
        $isIdentifiant = is_numeric($data);

        return $isEntity && $isIdentifiant;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $entity = $this->em->find($type, $data);

        return $entity;
    }

}