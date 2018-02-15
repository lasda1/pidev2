<?php

namespace EspaceEtudeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Documents
 *
 * @ORM\Table(name="documents")
 * @ORM\Entity(repositoryClass="EspaceEtudeBundle\Repository\DocumentsRepository")
 */
class Documents
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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="typeDocument", type="string", length=255)
     */
    private $typeDocument;

    /**
     * @var float
     *
     * @ORM\Column(name="size", type="float")
     */
    private $size;


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
     * Set path.
     *
     * @param string $path
     *
     * @return Documents
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set libelle.
     *
     * @param string $libelle
     *
     * @return Documents
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
     * Set date.
     *
     * @param string $date
     *
     * @return Documents
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
     * Set typeDocument.
     *
     * @param string $typeDocument
     *
     * @return Documents
     */
    public function setTypeDocument($typeDocument)
    {
        $this->typeDocument = $typeDocument;

        return $this;
    }

    /**
     * Get typeDocument.
     *
     * @return string
     */
    public function getTypeDocument()
    {
        return $this->typeDocument;
    }

    /**
     * Set size.
     *
     * @param float $size
     *
     * @return Documents
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return float
     */
    public function getSize()
    {
        return $this->size;
    }
}
