<?php

namespace ObjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Interaction
 *
 * @ORM\Table(name="interaction")
 * @ORM\Entity(repositoryClass="ObjetBundle\Repository\InteractionRepository")
 */
class Interaction
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user",referencedColumnName="id")
     */
    private $idUser;

    /**
     * @ORM\ManyToOne(targetEntity="ObjetBundle\Entity\Objet")
     * @ORM\JoinColumn(name="objet",referencedColumnName="id")
     */
    private $idObjet;

    /**
     * @var string
     *
     * @ORM\Column(name="Statut", type="string", length=255)
     */
    private $statut;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Interaction
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set idUser
     *
     * @param \UserBundle\Entity\User $idUser
     *
     * @return Interaction
     */
    public function setIdUser(\UserBundle\Entity\User $idUser = null)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \UserBundle\Entity\User
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set idObjet
     *
     * @param \ObjetBundle\Entity\Objet $idObjet
     *
     * @return Interaction
     */
    public function setIdObjet(\ObjetBundle\Entity\Objet $idObjet = null)
    {
        $this->idObjet = $idObjet;

        return $this;
    }

    /**
     * Get idObjet
     *
     * @return \ObjetBundle\Entity\Objet
     */
    public function getIdObjet()
    {
        return $this->idObjet;
    }
}
