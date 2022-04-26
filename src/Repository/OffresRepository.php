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


    public function Approuver($id){
        $em = $this->getEntityManager();
        $query = $em->createQuery("Update App\Entity\Offres C SET C.etat='1' where C.idOffre = :id");
        $query->setParameter('id', $id);
        return $query->getSingleScalarResult();
    }
/*
    public function NbrApparement($id){
        
 
        $em = $this->getEntityManager();
        $query = $em->createQuery('select COUNT(C) from App\Entity\Offres C where C.idUser = :id and C.type = :categ');
        $query->setParameter('id', $id);
        return $query->getSingleScalarResult();
    }*/


   
}