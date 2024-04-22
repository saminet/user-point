<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

     /**
      * @return User[] Returns an array of User objects
     */
    public function findByCriteria($value)
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.group', 'g');

        if(!empty($value)){
            $qb->andWhere('g.id = :groupId')
                ->setParameter('groupId', $value);
        }

        $query =
            $qb
                ->orderBy('u.createdAt', 'DESC')
                ->getQuery();



        return $query->getResult();
    }

}
