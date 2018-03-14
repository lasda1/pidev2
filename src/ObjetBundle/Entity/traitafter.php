<?php

namespace ObjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * traitafter
 *
 * @ORM\Table(name="traitafter")
 * @ORM\Entity(repositoryClass="ObjetBundle\Repository\traitafterRepository")
 */
class traitafter
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
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="ObjetBundle\Entity\Interaction")
     * @ORM\JoinColumn(name="interaction",referencedColumnName="id")
     */
    private $interaction;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;


    /**
     * Get id
     *
     * @return integer
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
     * @return traitafter
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
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return traitafter
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set interaction
     *
     * @param \ObjetBundle\Entity\Interaction $interaction
     *
     * @return traitafter
     */
    public function setInteraction(\ObjetBundle\Entity\Interaction $interaction = null)
    {
        $this->interaction = $interaction;

        return $this;
    }

    /**
     * Get interaction
     *
     * @return \ObjetBundle\Entity\Interaction
     */
    public function getInteraction()
    {
        return $this->interaction;
    }
}
