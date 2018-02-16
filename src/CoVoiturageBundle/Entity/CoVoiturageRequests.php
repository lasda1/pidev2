<?php

namespace CoVoiturageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoVoiturageRequests
 *
 * @ORM\Table(name="co_voiturage_requests")
 * @ORM\Entity(repositoryClass="CoVoiturageBundle\Repository\CoVoiturageRequestsRepository")
 */
class CoVoiturageRequests
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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;


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
     * Set idc
     *
     * @param \CoVoiturageBundle\Entity\CoVoiturage $idc
     *
     * @return CoVoiturageRequests
     */
    public function setIdc(\CoVoiturageBundle\Entity\CoVoiturage $idc = null)
    {
        $this->idc = $idc;

        return $this;
    }

    /**
     * Get idc
     *
     * @return \CoVoiturageBundle\Entity\CoVoiturage
     */
    public function getIdc()
    {
        return $this->idc;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return CoVoiturageRequests
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
}
