<?php

namespace App\Repository;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }
    public function findEventBytri(){
        $d=new \DateTime('now');

        $qb = $this->getEntityManager()->createQuery('SELECT e FROM App\Entity\Event e WHERE  e.datedebev >= :now ORDER BY e.datedebev ASC');
        $qb->setParameter('now', $d);
        return $qb->getResult();
    }

}