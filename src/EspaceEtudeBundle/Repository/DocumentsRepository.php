<?php

namespace EspaceEtudeBundle\Repository;

/**
 * DocumentsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DocumentsRepository extends \Doctrine\ORM\EntityRepository
{
    public function countAll()
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
