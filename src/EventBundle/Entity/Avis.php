<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avis
 *
 * @ORM\Table(name="avis", indexes={@ORM\Index(name="id", columns={"idevent"}),@ORM\Index(name="id", columns={"iduser"})})
 * @ORM\Entity(repositoryClass="EventBundle\Repository\AvisRepository")
 */
class Avis
{

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="iduser",referencedColumnName="id")
     * @ORM\Id
     */
    private $iduser;

    /**
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\Event")
     * @ORM\JoinColumn(name="idevent",referencedColumnName="id")
     * @ORM\Id
     */
    private $idevent;

    /**
     * @var integer
     *
     * @ORM\Column(name="avis", type="integer", nullable=false)
     */
    private $avis=0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * @param int $iduser
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;
    }

    /**
     * @return int
     */
    public function getIdevent()
    {
        return $this->idevent;
    }

    /**
     * @param int $idevent
     */
    public function setIdevent($idevent)
    {
        $this->idevent = $idevent;
    }

    /**
     * @return int
     */
    public function getAvis()
    {
        return $this->avis;
    }

    /**
     * @param int $avis
     */
    public function setAvis($avis)
    {
        $this->avis = $avis;
    }

}

