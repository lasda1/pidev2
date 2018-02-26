<?php

namespace EventBundle\Repository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends \Doctrine\ORM\EntityRepository
{
    function recherche($valeur){
        $query = EventRepository::createQueryBuilder('e')
            ->where('UPPER(e.titre) LIKE UPPER(:valeur)')
            ->orWhere('UPPER(e.categorie) Like UPPER(:valeur)')
            ->orWhere('UPPER(e.description) Like UPPER(:valeur)')
            ->orWhere('UPPER(e.lieu) Like UPPER(:valeur)')
            ->setParameter('valeur', '%'.$valeur.'%')
            ->orderBy('e.datedebut', 'DESC')
            ->getQuery();
        return $query->getResult();
    }

}
