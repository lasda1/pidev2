<?php

namespace ColocationBundle\Repository;

/**
 * ColocationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ColocationRepository extends \Doctrine\ORM\EntityRepository
{
    function recherche($valeur)
    {
        $query = ColocationRepository::createQueryBuilder('r')
            ->where('UPPER(r.ville) LIKE UPPER(:valeur)')
            ->setParameter('valeur', '%'.$valeur.'%')
            ->getQuery();
        return $query->getResult();
    }
}
