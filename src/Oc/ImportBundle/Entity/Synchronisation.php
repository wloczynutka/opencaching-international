<?php

namespace Oc\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Synchronisation
 *
 * @author Łza
 * @ORM\Entity
 * @ORM\Table(name="synchronisation")
 */
class Synchronisation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="remote_sysyem_id")
     * Oc\ImportBundle\Controller\DefaultController::originIdentifier
     */
    protected $remoteSystemId;

    /**
     * @ORM\Column(type="integer", name="revision")
     */
    protected $syncRevision;

    /**
     * @ORM\Column(type="datetime", name="datetime")
     */
    protected $syncDateTime;

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
     * Set remoteSystemId
     *
     * @param integer $remoteSystemId
     * @return Synchronisation
     */
    public function setRemoteSystemId($remoteSystemId)
    {
        $this->remoteSystemId = $remoteSystemId;

        return $this;
    }

    /**
     * Get remoteSystemId
     *
     * @return integer 
     */
    public function getRemoteSystemId()
    {
        return $this->remoteSystemId;
    }

    /**
     * Set syncRevision
     *
     * @param integer $syncRevision
     * @return Synchronisation
     */
    public function setSyncRevision($syncRevision)
    {
        $this->syncRevision = $syncRevision;

        return $this;
    }

    /**
     * Get syncRevision
     *
     * @return integer 
     */
    public function getSyncRevision()
    {
        return $this->syncRevision;
    }

    /**
     * Set syncDateTime
     *
     * @param \DateTime $syncDateTime
     * @return Synchronisation
     */
    public function setSyncDateTime($syncDateTime)
    {
        $this->syncDateTime = $syncDateTime;

        return $this;
    }

    /**
     * Get syncDateTime
     *
     * @return \DateTime 
     */
    public function getSyncDateTime()
    {
        return $this->syncDateTime;
    }
}
