<?php

namespace ObjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Signal
 *
 * @ORM\Table(name="signal")
 * @ORM\Entity(repositoryClass="ObjetBundle\Repository\SignalRepository")
 */
class Signal
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
     * @ORM\JoinColumn(name="user",referencedColumnName="id",nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="ObjetBundle\Entity\Interaction")
     * @ORM\JoinColumn(name="id",referencedColumnName="id",nullable=false)
     */
    private $interaction;

    /**
     * @var string
     *
     * @ORM\Column(name="Statut", type="string", length=255,nullable=false)
     */
    private $statut="Pas de Signal";
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
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Signal
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
     * Set statut
     *
     * @param string $statut
     *
     * @return Signal
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
     * Set interaction
     *
     * @param \ObjetBundle\Entity\Interaction $interaction
     *
     * @return Signal
     */
    public function setInteraction(\ObjetBundle\Entity\Interaction $interaction)
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
