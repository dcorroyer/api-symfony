<?php

namespace App\Repository;

use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicule>
 *
 * @method Vehicule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicule[]    findAll()
 * @method Vehicule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }

    /**
     * @param mixed $user
     * @return array
     */
    public function findVehiculesFromUser(mixed $user): array
    {
        return $this->createQueryBuilder('vehicule')
            ->where('vehicule.user = :user_id')
            ->setParameter('user_id', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param mixed $user
     * @param int $vehiculeId
     * @return Vehicule|null
     */
    public function findVehiculeFromUser(mixed $user, int $vehiculeId): ?Vehicule
    {
        try {
            return $this->createQueryBuilder('vehicule')
                ->where('vehicule.user = :user_id')
                ->setParameter('user_id', $user)
                ->andWhere('vehicule.id = :id')
                ->setParameter('id', $vehiculeId)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return null;
        }
    }
}
