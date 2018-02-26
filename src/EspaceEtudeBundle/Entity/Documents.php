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
     * @var integer
     *
     * @ORM\Column(name="flag", type="integer")
     */
    private $flag;
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
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=255)
     */
    private $language;
    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=255)
     */
    private $img;
    /**
     *
     * @ORM\ManyToOne(targetEntity="EspaceEtudeBundle\Entity\Matiere")
     * @ORM\JoinColumn(name="id_matiere", referencedColumnName="id")
     */
    private $matiere;

    /**
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $user;




    /**
     * Documents constructor.
     */
    public function __construct()
    {
    }


    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;
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

    /**
     * Set matiere.
     *
     * @param \EspaceEtudeBundle\Entity\Matiere|null $matiere
     *
     * @return Documents
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
     * Set user.
     *
     * @param \UserBundle\Entity\User|null $user
     *
     * @return Documents
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \UserBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set img.
     *
     * @param string $img
     *
     * @return Documents
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img.
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set flag.
     *
     * @param int $flag
     *
     * @return Documents
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag.
     *
     * @return int
     */
    public function getFlag()
    {
        return $this->flag;
    }
}
