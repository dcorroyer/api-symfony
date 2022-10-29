<?php

namespace App\Repository;

use App\Entity\Maintenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Maintenance>
 *
 * @method Maintenance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maintenance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maintenance[]    findAll()
 * @method Maintenance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaintenanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Maintenance::class);
    }

    /**
     * @param mixed $vehicule
     * @param int $maintenanceId
     * @return Maintenance|null
     */
    public function findMaintenanceFromVehicule(mixed $vehicule, int $maintenanceId): ?Maintenance
    {
        try {
            return $this->createQueryBuilder('maintenance')
                ->where('maintenance.vehicule = :vehicule_id')
                ->setParameter('vehicule_id', $vehicule)
                ->andWhere('maintenance.id = :id')
                ->setParameter('id', $maintenanceId)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return null;
        }
    }
}
