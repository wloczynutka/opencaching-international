<?php

namespace Oc\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="geocaches_attributes")
 */
class GeoCacheAttribute
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="GeoCache", inversedBy="atttributes")
     * @ORM\JoinColumn(name="geocache_id", referencedColumnName="id")
     */
    protected $geoCacheId;

    /**
     * @ORM\Column(type="string", length=2)
     */
	protected $identifier;
  

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
     * Set attributeId
     *
     * @param string $identifier
     * @return GeoCacheAttribute
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get attributeId
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set geoCacheId
     *
     * @param \Oc\CoreBundle\Entity\GeoCache $geoCacheId
     * @return GeoCacheAttribute
     */
    public function setGeoCacheId(\Oc\CoreBundle\Entity\GeoCache $geoCacheId = null)
    {
        $this->geoCacheId = $geoCacheId;
        return $this;
    }

    /**
     * Get geoCacheId
     *
     * @return \Oc\CoreBundle\Entity\GeoCache 
     */
    public function getGeoCacheId()
    {
        return $this->geoCacheId;
    }
}
