<?php

namespace ObjetBundle\Repository;

/**
 * ObjetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ObjetRepository extends \Doctrine\ORM\EntityRepository
{
    public function objperd(){
        $q = $this->getEntityManager()
            ->createQuery("select o from ObjetBundle:Objet o WHERE o.nature='Objet Perdu'");
        return $q->getResult();

    }
   public function objtrouv(){
        $q = $this->getEntityManager()
            ->createQuery("select o from ObjetBundle:Objet o WHERE o.nature='Objet Trouvé'");
        return $q->getResult();
    }

    public function nbobjperd(){
        $q = $this->getEntityManager()
            ->createQuery("select COUNT (o) from ObjetBundle:Objet o WHERE o.type='Chargeur'");
        return $q->getSingleScalarResult();;

    }

    public function nbobjtrouv(){
        $q = $this->getEntityManager()
            ->createQuery("select o from ObjetBundle:Objet o WHERE o.nature='Objet Trouvé' COUNT ");
        return $q->getResult();
    }

    function recherche($search){
        $query = ObjetRepository::createQueryBuilder('e')
            ->where('UPPER(e.type) LIKE UPPER(:search)')
            ->setParameter('valeur', '%'.$search.'%')
            ->getQuery();
        return $query->getResult();
    }















}
