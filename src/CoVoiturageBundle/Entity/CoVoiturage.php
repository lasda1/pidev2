<?php

namespace CoVoiturageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoVoiturage
 *
 * @ORM\Table(name="co_voiturage")
 * @ORM\Entity(repositoryClass="CoVoiturageBundle\Repository\CoVoiturageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CoVoiturage
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=1)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;


    /**
     * @var string
     *
     * @ORM\Column(name="depart", type="string", length=255)
     */
    private $depart;

    /**
     * @var string
     *
     * @ORM\Column(name="depart_id", type="string", length=255)
     */
    private $departId;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=255)
     */
    private $destination;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_id", type="string", length=255)
     */
    private $destinationId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime" , nullable=true)
     */
    private $date;


    /**
     * @var string
     *
     * @ORM\Column(name="onetime", type="string")
     */
    private $onetime;

    /**
     * @var int
     *
     * @ORM\Column(name="placedisponibles", type="integer", nullable=true)
     */
    private $placedisponibles;

    /**
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(type="datetime", nullable = true)
     */
    protected $updated;


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
     * Set depart
     *
     * @param string $depart
     *
     * @return CoVoiturage
     */
    public function setDepart($depart)
    {
        $this->depart = $depart;

        return $this;
    }

    /**
     * Get depart
     *
     * @return string
     */
    public function getDepart()
    {
        return $this->depart;
    }

    /**
     * Set destination
     *
     * @param string $destination
     *
     * @return CoVoiturage
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return CoVoiturage
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * Set onetime
     *
     * @param integer $onetime
     *
     * @return CoVoiturage
     */
    public function setOnetime($onetime)
    {
        $this->onetime = $onetime;

        return $this;
    }

    /**
     * Get onetime
     *
     * @return int
     */
    public function getOnetime()
    {
        return $this->onetime;
    }

    /**
     * Set placedisponibles
     *
     * @param integer $placedisponibles
     *
     * @return CoVoiturage
     */
    public function setPlacedisponibles($placedisponibles)
    {
        $this->placedisponibles = $placedisponibles;

        return $this;
    }

    /**
     * Get placedisponibles
     *
     * @return int
     */
    public function getPlacedisponibles()
    {
        return $this->placedisponibles;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return CoVoiturage
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return CoVoiturage
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
     * Set departId
     *
     * @param string $departId
     *
     * @return CoVoiturage
     */
    public function setDepartId($departId)
    {
        $this->departId = $departId;

        return $this;
    }

    /**
     * Get departId
     *
     * @return string
     */
    public function getDepartId()
    {
        return $this->departId;
    }

    /**
     * Set destinationId
     *
     * @param string $destinationId
     *
     * @return CoVoiturage
     */
    public function setDestinationId($destinationId)
    {
        $this->destinationId = $destinationId;

        return $this;
    }

    /**
     * Get destinationId
     *
     * @return string
     */
    public function getDestinationId()
    {
        return $this->destinationId;
    }

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime("now");
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return CoVoiturage
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return CoVoiturage
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
