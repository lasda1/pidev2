<?php

namespace CoVoiturageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoVoiturageDays
 *
 * @ORM\Table(name="co_voiturage_days")
 * @ORM\Entity(repositoryClass="CoVoiturageBundle\Repository\CoVoiturageDaysRepository")
 */
class CoVoiturageDays
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
     * @ORM\ManyToOne(targetEntity="CoVoiturage")
     * @ORM\JoinColumn(name="idc", referencedColumnName="id")
     */
    private $idc;

    /**
     * @return mixed
     */
    public function getIdc()
    {
        return $this->idc;
    }

    /**
     * @param mixed $idc
     */
    public function setIdc($idc)
    {
        $this->idc = $idc;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="lundi", type="string", length=1, nullable=true)
     */
    private $lundi;

    /**
     * @var string
     *
     * @ORM\Column(name="mardi", type="string", length=1, nullable=true)
     */
    private $mardi;

    /**
     * @var string
     *
     * @ORM\Column(name="mercredi", type="string", length=1, nullable=true)
     */
    private $mercredi;

    /**
     * @var string
     *
     * @ORM\Column(name="jeudi", type="string", length=1, nullable=true)
     */
    private $jeudi;

    /**
     * @var string
     *
     * @ORM\Column(name="vendredi", type="string", length=1, nullable=true)
     */
    private $vendredi;

    /**
     * @var string
     *
     * @ORM\Column(name="samedi", type="string", length=1, nullable=true)
     */
    private $samedi;

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
     * @param string $lundi
     */
    public function setLundi($lundi)
    {
        $this->lundi = $lundi;
    }

    /**
     * Get lundi
     *
     * @return string
     */
    public function getLundi()
    {
        return $this->lundi;
    }

    /**
     * @return string
     */
    public function getMardi()
    {
        return $this->mardi;
    }

    /**
     * @param string $mardi
     */
    public function setMardi($mardi)
    {
        $this->mardi = $mardi;
    }

    /**
     * @return string
     */
    public function getMercredi()
    {
        return $this->mercredi;
    }

    /**
     * @param string $mercredi
     */
    public function setMercredi($mercredi)
    {
        $this->mercredi = $mercredi;
    }

    /**
     * @return string
     */
    public function getJeudi()
    {
        return $this->jeudi;
    }

    /**
     * @param string $jeudi
     */
    public function setJeudi($jeudi)
    {
        $this->jeudi = $jeudi;
    }

    /**
     * @return string
     */
    public function getVendredi()
    {
        return $this->vendredi;
    }

    /**
     * @param string $vendredi
     */
    public function setVendredi($vendredi)
    {
        $this->vendredi = $vendredi;
    }

    /**
     * @return string
     */
    public function getSamedi()
    {
        return $this->samedi;
    }

    /**
     * @param string $samedi
     */
    public function setSamedi($samedi)
    {
        $this->samedi = $samedi;
    }



}
