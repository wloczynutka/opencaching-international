<?php

namespace Oc\CoreBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="origin_identifier")
     */
    protected $originIdentifier;

    /**
     * @ORM\Column(type="guid", name="uuid")
     */
    protected $uuid;

    /**
     * @ORM\OneToMany(targetEntity="GeoCache", mappedBy="owner")
     */
    protected $ownedGeoCaches;

    /**
     * @ORM\Column(type="string")
     * url to origin user profile
     */
    protected $url;


    public function __construct()
    {
        parent::__construct();
        $this->loadOwnedGeocaches();
    }

	public function loadOwnedGeocaches() {
		$this->ownedGeoCaches = new ArrayCollection();
	}

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
     * Set originIdentifier
     *
     * @param integer $originIdentifier
     * @return User
     */
    public function setOriginIdentifier($originIdentifier)
    {
        $this->originIdentifier = $originIdentifier;
        return $this;
    }

    /**
     * Get originIdentifier
     *
     * @return integer 
     */
    public function getOriginIdentifier()
    {
        return $this->originIdentifier;
    }

    /**
     * Set uuid
     *
     * @param guid $uuid
     * @return User
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * Get uuid
     *
     * @return guid 
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Add ownedGeoCaches
     *
     * @param \Oc\CoreBundle\Entity\GeoCache $ownedGeoCaches
     * @return User
     */
    public function addOwnedGeoCach(\Oc\CoreBundle\Entity\GeoCache $ownedGeoCaches)
    {
        $this->ownedGeoCaches[] = $ownedGeoCaches;
        return $this;
    }

    /**
     * Remove ownedGeoCaches
     *
     * @param \Oc\CoreBundle\Entity\GeoCache $ownedGeoCaches
     */
    public function removeOwnedGeoCach(\Oc\CoreBundle\Entity\GeoCache $ownedGeoCaches)
    {
        $this->ownedGeoCaches->removeElement($ownedGeoCaches);
    }

    /**
     * Get ownedGeoCaches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOwnedGeoCaches()
    {
        return $this->ownedGeoCaches;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return User
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }
}
