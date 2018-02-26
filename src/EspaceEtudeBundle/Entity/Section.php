<?php

namespace EspaceEtudeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EspaceEtudeBundle\Enum\Niveau;
use EspaceEtudeBundle\Enum\NiveauEnum;

/**
 * Section
 *
 * @ORM\Table(name="section")
 * @ORM\Entity(repositoryClass="EspaceEtudeBundle\Repository\SectionRepository")
 */
class Section
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
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var string
     * @ORM\Column(name="niveau", type="string", length=255, nullable=false)
     */
    private $niveau;


    /**
     * @ORM\ManyToMany(targetEntity="EspaceEtudeBundle\Entity\Matiere", cascade={"persist"})
     */
    private $matiere;


    /**
     * @return string
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * @param string $niveau
     */
    public function setNiveau($niveau)
    {
        if (!in_array($niveau, Niveau::getAvailableTypes())) {
            throw new \InvalidArgumentException("Invalid type");
        }

        $this->niveau = $niveau;

        return $this;
    }




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
     * Set libelle.
     *
     * @param string $libelle
     *
     * @return Section
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle.
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->matiere = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add matiere.
     *
     * @param \EspaceEtudeBundle\Entity\Matiere $matiere
     *
     * @return Section
     */
    public function addMatiere(\EspaceEtudeBundle\Entity\Matiere $matiere)
    {
        $this->matiere[] = $matiere;

        return $this;
    }

    /**
     * Remove matiere.
     *
     * @param \EspaceEtudeBundle\Entity\Matiere $matiere
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMatiere(\EspaceEtudeBundle\Entity\Matiere $matiere)
    {
        return $this->matiere->removeElement($matiere);
    }

    /**
     * Get matiere.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatiere()
    {
        return $this->matiere;
    }
}
