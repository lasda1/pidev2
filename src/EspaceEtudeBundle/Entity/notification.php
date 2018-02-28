<?php

namespace EspaceEtudeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * notification
 *
 * @ORM\Table(name="notification_documents")
 * @ORM\Entity(repositoryClass="EspaceEtudeBundle\Repository\notificationRepository")
 */
class notification
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
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;
    /**
     * @var string
     *
     * @ORM\Column(name="vu", type="string", length=255)
     */
    private $vu;
    /**
     *
     * @ORM\ManyToOne(targetEntity="EspaceEtudeBundle\Entity\Matiere")
     * @ORM\JoinColumn(name="id_matiere", referencedColumnName="id")
     */
    private $matiere;
    /**
     *
     * @ORM\ManyToOne(targetEntity="EspaceEtudeBundle\Entity\Documents")
     * @ORM\JoinColumn(name="id_doc", referencedColumnName="id", onDelete="CASCADE")
     */
    private $document;



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param string $date
     *
     * @return notification
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set vu.
     *
     * @param string $vu
     *
     * @return notification
     */
    public function setVu($vu)
    {
        $this->vu = $vu;

        return $this;
    }

    /**
     * Get vu.
     *
     * @return string
     */
    public function getVu()
    {
        return $this->vu;
    }

    /**
     * Set matiere.
     *
     * @param \EspaceEtudeBundle\Entity\Matiere|null $matiere
     *
     * @return notification
     */
    public function setMatiere(\EspaceEtudeBundle\Entity\Matiere $matiere = null)
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * Get matiere.
     *
     * @return \EspaceEtudeBundle\Entity\Matiere|null
     */
    public function getMatiere()
    {
        return $this->matiere;
    }

    /**
     * Set document.
     *
     * @param \EspaceEtudeBundle\Entity\Documents|null $document
     *
     * @return notification
     */
    public function setDocument(\EspaceEtudeBundle\Entity\Documents $document = null)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document.
     *
     * @return \EspaceEtudeBundle\Entity\Documents|null
     */
    public function getDocument()
    {
        return $this->document;
    }



}
