<?php
namespace EventBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\EventRepository")
 */
class Event
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=6000, nullable=false)
     */
    private $description;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="datetime", nullable=false)
     */
    private $datedebut;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user",referencedColumnName="id")
     */
    private $user;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime", nullable=true)
     */
    private $datefin;
    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=255, nullable=false)
     */
    private $lieu;
    /**
     * @var float
     *
     * @ORM\Column(name="x", type="float", nullable=false, length=70)
     */
    private $x;
    /**
     * @var float
     *
     * @ORM\Column(name="y", type="float", nullable=false, length=70)
     */
    private $y;




    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=false)
     */
    private $photo;
    /**
     * @var integer
     *
     * @ORM\Column(name="nb_max", type="integer", nullable=true)
     */
    private $nbMax;
    /**
     * @var boolean
     *
     * @ORM\Column(name="enable", type="boolean", nullable=false)
     */
    private $enable=1;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255, nullable=false)
     */
    private $categorie;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=false)
     */
    private $createdAt;



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
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }
    /**
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * @return \DateTime
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }
    /**
     * @param \DateTime $datedebut
     */
    public function setDatedebut($datedebut)
    {
        $this->datedebut = $datedebut;
    }
    /**
     * @return \DateTime
     */
    public function getDatefin()
    {
        return $this->datefin;
    }
    /**
     * @param \DateTime $datefin
     */
    public function setDatefin($datefin)
    {
        $this->datefin = $datefin;
    }
    /**
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }
    /**
     * @param string $lieu
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    }
    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }
    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }
    /**
     * @return int
     */
    public function getNbMax()
    {
        return $this->nbMax;
    }
    /**
     * @param int $nbMax
     */
    public function setNbMax($nbMax)
    {
        $this->nbMax = $nbMax;
    }
    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->enable;
    }
    /**
     * @param bool $enable
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
    }
    /**
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
    /**
     * @param string $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return float
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param float $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return float
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param float $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

}

