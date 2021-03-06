<?php

namespace Oc\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oc\CoreBundle\Entity\GeoCacheWaypointType;

/**
 * @ORM\Entity
 * @ORM\Table(name="geocaches_waypoints")
 * @ORM\HasLifecycleCallbacks
 */
class GeoCacheWaypoint
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="GeoCache", inversedBy="$waypoints")
     * @ORM\JoinColumn(name="geocache_id", referencedColumnName="id")
     */
    protected $geoCache;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="float")
     */
    protected $latitude;

    /**
     * @ORM\Column(type="float")
     */
    protected $longitude;

    /**
     * @ORM\ManyToOne(targetEntity="GeoCacheWaypointType")
     * @ORM\JoinColumn(name="geocaches_waypoint_types_id", referencedColumnName="id")
     **/
    protected $type;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * coordinates object
     * @var $coordinates \Oc\CoreBundle\Coordinates;
     */
    private $coordinates;


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
     * Set name
     *
     * @param string $name
     * @return GeoCacheWaypoint
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return GeoCacheWaypoint
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return GeoCacheWaypoint
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return GeoCacheWaypoint
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set geoCache
     *
     * @param \Oc\CoreBundle\Entity\GeoCache $geoCache
     * @return GeoCacheWaypoint
     */
    public function setGeoCache(\Oc\CoreBundle\Entity\GeoCache $geoCache = null)
    {
        $this->geoCache = $geoCache;

        return $this;
    }

    /**
     * Get geoCache
     *
     * @return \Oc\CoreBundle\Entity\GeoCache 
     */
    public function getGeoCache()
    {
        return $this->geoCache;
    }

    /**
     * Set type
     *
     * @param \Oc\CoreBundle\Entity\GeoCacheWaypointType $type
     * @return GeoCacheWaypoint
     */
    public function setType(\Oc\CoreBundle\Entity\GeoCacheWaypointType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Oc\CoreBundle\Entity\GeoCacheWaypointType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return \Oc\CoreBundle\Coordinates
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /** @ORM\PostLoad */
    public function doStuffOnPostLoad()
    {
        $this->coordinates = new \Oc\CoreBundle\Coordinates(array('latitude' => $this->latitude, 'longitude' => $this->longitude));
    }
}
