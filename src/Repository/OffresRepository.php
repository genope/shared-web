<?php


namespace App\Repository;

use App\Entity\Offres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offres[]    findAll()
 * @method Offres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offres::class);
    }


    public function NbrOffre($id){
        
 
        $em = $this->getEntityManager();
        $query = $em->createQuery('select COUNT(C) from App\Entity\Offres C where C.idUser = :id');
        $query->setParameter('id', $id);
        return $query->getSingleScalarResult();
    }


    public function Approuver($editId){
            $queryBuilder = $this->em->createQueryBuilder();
            $query = $queryBuilder->update('App\Entity\Offres', 'u')
                ->set('u.etat', '1')
                ->where('u.id = :editId')
                ->setParameter('editId', $editId)
                ->getQuery();
            $result = $query->execute();
    }
/*
    public function NbrApparement($id){
        
 
        $em = $this->getEntityManager();
        $query = $em->createQuery('select COUNT(C) from App\Entity\Offres C where C.idUser = :id and C.type = :categ');
        $query->setParameter('id', $id);
        return $query->getSingleScalarResult();
    }*/


   
}