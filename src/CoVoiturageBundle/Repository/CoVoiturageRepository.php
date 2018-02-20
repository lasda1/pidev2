<?php

namespace CoVoiturageBundle\Repository;

/**
 * CoVoiturageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CoVoiturageRepository extends \Doctrine\ORM\EntityRepository
{

    public function getLastThree($type)
    {
        $q = $this->getEntityManager()->createQuery("SELECT p
              FROM CoVoiturageBundle:CoVoiturage p
              WHERE p.type = :type
              ORDER BY p.updated DESC , p.created DESC")->setParameter('type',$type);
        return $q->setMaxResults(3)->getResult();
    }

    public function getAllDesc($type)
    {
        $q = $this->getEntityManager()->createQuery("SELECT p
              FROM CoVoiturageBundle:CoVoiturage p
              WHERE p.type = :type AND ( p.date > CURRENT_DATE() OR p.onetime = 'on' ) AND p.placedisponibles > 0
              ORDER BY p.updated DESC , p.created DESC")->setParameter('type',$type);
        return $q->getResult();
    }

    public function getAllDescD($type)
    {
        $q = $this->getEntityManager()->createQuery("SELECT p
              FROM CoVoiturageBundle:CoVoiturage p
              WHERE p.type = :type AND ( p.date > CURRENT_DATE() OR p.onetime = 'on' )
              ORDER BY p.updated DESC , p.created DESC")->setParameter('type',$type);
        return $q->getResult();
    }

    public function getAllDesc2($type)
    {
        $q = $this->getEntityManager()->createQuery("SELECT p
              FROM CoVoiturageBundle:CoVoiturage p
              WHERE p.type = :type AND ( p.date > CURRENT_DATE() OR p.onetime = 'on' ) AND p.placedisponibles > 0
              ORDER BY p.updated DESC , p.created DESC")->setParameter('type',$type);
        return $q;
    }

    public function getOwn($type,$user)
    {
        $q = $this->getEntityManager()->createQuery("SELECT p
              FROM CoVoiturageBundle:CoVoiturage p
              WHERE p.type = :type AND ( p.date > CURRENT_DATE() OR p.onetime = 'on' ) AND p.user = :user
              ORDER BY p.updated DESC , p.created DESC")->setParameter('type',$type)->setParameter('user',$user);
        return $q->getResult();
    }

}
