<?php

namespace MyAppMailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reponse
 *
 * @ORM\Table(name="reponse")
 * @ORM\Entity(repositoryClass="MyAppMailBundle\Repository\ReponseRepository")
 */
class Reponse
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var text
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="reponses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $sender;


    /**
     * @ORM\ManyToOne(targetEntity="ColocationBundle\Entity\Colocation", inversedBy="reponses")
     * @ORM\JoinColumn(name="colocation_id", referencedColumnName="id")
     */
    private $colocation;

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
     * Set titre
     *
     * @param string $titre
     *
     * @return Reponse
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Reponse
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }



    /**
     * Set sender
     *
     * @param \UserBundle\Entity\User $sender
     *
     * @return Reponse
     */
    public function setSender(\UserBundle\Entity\User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \UserBundle\Entity\User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set colocation
     *
     * @param \ColocationBundle\Entity\Colocation $colocation
     *
     * @return Reponse
     */
    public function setColocation(\ColocationBundle\Entity\Colocation $colocation = null)
    {
        $this->colocation = $colocation;

        return $this;
    }

    /**
     * Get colocation
     *
     * @return \ColocationBundle\Entity\Colocation
     */
    public function getColocation()
    {
        return $this->colocation;
    }
}
