<?php

namespace Oc\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of geoCache
 *
 * @author Łza
 * @ORM\Entity
 * @ORM\Table(name="geocaches", indexes={@ORM\Index(name="search_idx", columns={"code"})})
 * @ORM\HasLifecycleCallbacks
 */
class GeoCache
{
    const TYPE_OTHERTYPE = 1;
    const TYPE_TRADITIONAL = 2;
    const TYPE_MULTICACHE = 3;
    const TYPE_VIRTUAL = 4;
    const TYPE_WEBCAM = 5;
    const TYPE_EVENT = 6;
    const TYPE_QUIZ = 7;
    const TYPE_MOVING = 8;
    const TYPE_GEOPATHFINAL = 9;
    const TYPE_OWNCACHE = 10;

    const STATUS_READY = 1;
    const STATUS_UNAVAILABLE = 2;
    const STATUS_ARCHIVED = 3;
    const STATUS_WAITAPPROVERS = 4;
    const STATUS_NOTYETAVAILABLE = 5;
    const STATUS_BLOCKED = 6;

    const SIZE_NOTSPECIFIED = 0;
    const SIZE_MICRO = 1;
    const SIZE_SMALL = 2;
    const SIZE_REGULAR = 3;
    const SIZE_LARGE= 4;
    const SIZE_XXL= 5;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=8, unique=true)
     */
    protected $code;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $source;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $type;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @ORM\Column(type="float")
     */
    protected $latitude;

    /**
     * @ORM\Column(type="float")
     */
    protected $longitude;

    /**
     * @ORM\Column(type="datetime", name="date_placed")
     */
    protected $datePlaced;

    /**
     * @ORM\Column(type="datetime", name="date_modified")
     */

    protected $dateModified;

    /**
     * @ORM\Column(type="datetime", name="date_created")
     */
    protected $dateCreated;

    /**
     * @ORM\Column(type="string")
     */
    protected $url;

    /**
     * @ORM\Column(type="integer")
     */
	protected $foundCount;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $notFoundCount;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $willattendCount;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $size;

	/**
	 * @ORM\Column(type="decimal")
	 */
	protected $difficulty;

	/**
	 * @ORM\Column(type="decimal")
	 */
	protected $terrain;

	/**
	 * @ORM\Column(type="float")
	 */
	protected $rating;
	
	/**
	 * @ORM\Column(type="integer", name="rating_votes_count")
	 */	
	protected $ratingVotesCount;
	
	/**
	 * @ORM\Column(type="integer")
	 */	
	protected $recommendations;

	 /**
     * @ORM\Column(type="datetime", name="last_found")
     */
	protected $lastFound;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $country;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $state = null;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="geoCacheId")
     */
	protected $images;

    /**
     * @ORM\OneToMany(targetEntity="GeoCacheDescription", mappedBy="geoCacheId")
     */
	protected $descriptions;

    /**
     * @ORM\OneToMany(targetEntity="GeoCacheAttribute", mappedBy="geoCacheId")
     */
	protected $attributes;

    /**
     * @ORM\OneToMany(targetEntity="GeoCacheWaypoint", mappedBy="geoCache")
     */
	protected $waypoints;

    /**
     * @ORM\OneToMany(targetEntity="GeoCacheLog", mappedBy="geoCache")
     */
	protected $logs;



    private $cacheLocation = array();


    /**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="ownedGeoCaches")
     * @ORM\JoinColumn(name="owner", referencedColumnName="id")
	 * @var $owner User
	 */
    private $owner;

    /**
	 *  @var $altitude \Oc\CoreBundle\Entity\Altitude
	 */
    private $altitude;

	/**
	 * coordinates object
	 * @var $coordinates \Oc\CoreBundle\Coordinates;
	 */
	private $coordinates;

    /**
     *
     * @param array $params
     *  'cacheId' => (integer) database cache identifier
     *  'wpId' => (string) geoCache wayPoint (ex. OP21F4)
     */
    public function __construct()
    {
		$this->images = new ArrayCollection();
		$this->descriptions = new ArrayCollection();
//		$this->coordinates = new \Oc\CoreBundle\Coordinates(array('latitude' => $this->latitude, 'longitude' => $this->longitude));

//        $this->datePlaced = strtotime($cacheDbRow['date_hidden']);
//        $this->loadCacheLocation($db);
//        $this->altitude = new \lib\Objects\GeoCache\Altitude($this);
//        $this->owner = new \lib\Objects\User\User($cacheDbRow['user_id']);
    }

    private function loadCacheLocation()
    {
        $db = DataBaseSingleton::Instance();
        $query = 'SELECT `code1`, `code2`, `code3`, `code4`  FROM `cache_location` WHERE `cache_id` =:1 LIMIT 1';
        $db->multiVariableQuery($query, $this->id);
        $dbResult = $db->dbResultFetch();
        $this->cacheLocation = $dbResult;
    }

    public function getCacheType()
    {
        return $this->cacheType;
    }

    public function getCacheLocation()
    {
        return $this->cacheLocation;
    }

    public function getCacheName()
    {
        return $this->cacheName;
    }

    public function getDatePlaced()
    {
        return $this->datePlaced;
    }

    /**
     * @return \Oc\CoreBundle\Coordinates
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @return \lib\Objects\GeoCache\Altitude
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    public function getCacheId()
    {
        return $this->id;
    }

    public function getWaypointId()
    {
        return $this->code;
    }

    /**
     * @return \lib\Objects\User\User
     */
    public function getOwner()
    {
        return $this->owner;
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
     * Set code
     *
     * @param string $code
     * @return GeoCache
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return GeoCache
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
     * Set type
     *
     * @param integer $type
     * @return GeoCache
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return GeoCache
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
     * @return GeoCache
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
     * Set datePlaced
     *
     * @param \DateTime $datePlaced
     * @return GeoCache
     */
    public function setDatePlaced(\DateTime $datePlaced)
    {
        $this->datePlaced = $datePlaced;
        return $this;
    }

    /**
     * Set dateModified
     *
     * @param \DateTime $dateModified
     * @return GeoCache
     */
    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime 
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return GeoCache
     */
    public function setDateCreated(\DateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return GeoCache
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

    /**
     * Set foundCount
     *
     * @param integer $foundCount
     * @return GeoCache
     */
    public function setFoundCount($foundCount)
    {
        $this->foundCount = $foundCount;

        return $this;
    }

    /**
     * Get foundCount
     *
     * @return integer 
     */
    public function getFoundCount()
    {
        return $this->foundCount;
    }

    /**
     * Set notFoundCount
     *
     * @param integer $notFoundCount
     * @return GeoCache
     */
    public function setNotFoundCount($notFoundCount)
    {
        $this->notFoundCount = $notFoundCount;

        return $this;
    }

    /**
     * Get notFoundCount
     *
     * @return integer 
     */
    public function getNotFoundCount()
    {
        return $this->notFoundCount;
    }

    /**
     * Set willattendCount
     *
     * @param integer $willattendCount
     * @return GeoCache
     */
    public function setWillattendCount($willattendCount)
    {
        $this->willattendCount = $willattendCount;

        return $this;
    }

    /**
     * Get willattendCount
     *
     * @return integer 
     */
    public function getWillattendCount()
    {
        return $this->willattendCount;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return GeoCache
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set difficulty
     *
     * @param integer $difficulty
     * @return GeoCache
     */
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * Get difficulty
     *
     * @return integer 
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * Set terrain
     *
     * @param integer $terrain
     * @return GeoCache
     */
    public function setTerrain($terrain)
    {
        $this->terrain = $terrain;

        return $this;
    }

    /**
     * Get terrain
     *
     * @return integer 
     */
    public function getTerrain()
    {
        return $this->terrain;
    }

    /**
     * Set rating
     * @param string $rating
     * @return GeoCache
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set ratingVotesCount
     *
     * @param integer $ratingVotesCount
     * @return GeoCache
     */
    public function setRatingVotesCount($ratingVotesCount)
    {
        $this->ratingVotesCount = $ratingVotesCount;

        return $this;
    }

    /**
     * Get ratingVotesCount
     *
     * @return integer 
     */
    public function getRatingVotesCount()
    {
        return $this->ratingVotesCount;
    }

    /**
     * Set recommendations
     *
     * @param integer $recommendations
     * @return GeoCache
     */
    public function setRecommendations($recommendations)
    {
        $this->recommendations = $recommendations;

        return $this;
    }

    /**
     * Get recommendations
     *
     * @return integer 
     */
    public function getRecommendations()
    {
        return $this->recommendations;
    }

    /**
     * Set lastFound
     *
     * @param \DateTime $lastFound
     * @return GeoCache
     */
    public function setLastFound($lastFound)
    {
        $this->lastFound = $lastFound;
        return $this;
    }

    /**
     * Get lastFound
     *
     * @return \DateTime 
     */
    public function getLastFound()
    {
        return $this->lastFound;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return GeoCache
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return GeoCache
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Add images
     *
     * @param \Oc\CoreBundle\Entity\Image $image
     * @return GeoCache
     */
    public function addImage(\Oc\CoreBundle\Entity\Image $image)
    {
        $this->images[] = $image;
        return $this;
    }

    /**
     * Remove images
     *
     * @param \Oc\CoreBundle\Entity\Image $image
     */
    public function removeImage(\Oc\CoreBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set owner
     *
     * @param \Oc\CoreBundle\Entity\User $owner
     * @return GeoCache
     */
    public function setOwner(\Oc\CoreBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Add descriptions
     *
     * @param \Oc\CoreBundle\Entity\GeoCacheDescription $descriptions
     * @return GeoCache
     */
    public function addDescription(\Oc\CoreBundle\Entity\GeoCacheDescription $descriptions)
    {
        $this->descriptions[] = $descriptions;

        return $this;
    }

    /**
     * Remove descriptions
     *
     * @param \Oc\CoreBundle\Entity\GeoCacheDescription $descriptions
     */
    public function removeDescription(\Oc\CoreBundle\Entity\GeoCacheDescription $descriptions)
    {
        $this->descriptions->removeElement($descriptions);
    }

    /**
     * Get descriptions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDescriptions()
    {
        return $this->descriptions;
    }

    /**
     * Add atttributes
     *
     * @param \Oc\CoreBundle\Entity\GeoCacheAttribute $atttributes
     * @return GeoCache
     */
    public function addAtttribute(\Oc\CoreBundle\Entity\GeoCacheAttribute $atttributes)
    {
        $this->attributes[] = $atttributes;

        return $this;
    }

    /**
     * Remove atttributes
     *
     * @param \Oc\CoreBundle\Entity\GeoCacheAttribute $atttributes
     */
    public function removeAtttribute(\Oc\CoreBundle\Entity\GeoCacheAttribute $atttributes)
    {
        $this->attributes->removeElement($atttributes);
    }

    /**
     * Get atttributes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return GeoCache
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add attributes
     *
     * @param \Oc\CoreBundle\Entity\GeoCacheAttribute $attribute
     * @return GeoCache
     */
    public function addAttribute(\Oc\CoreBundle\Entity\GeoCacheAttribute $attribute)
    {
        $this->attributes[] = $attribute;
        return $this;
    }

    /**
     * Remove attributes
     *
     * @param \Oc\CoreBundle\Entity\GeoCacheAttribute $attributes
     */
    public function removeAttribute(\Oc\CoreBundle\Entity\GeoCacheAttribute $attributes)
    {
        $this->attributes->removeElement($attributes);
    }

    /**
     * Add waypoints
     *
     * @param \Oc\CoreBundle\Entity\GeoCacheWaypoint $waypoint
     * @return GeoCache
     */
    public function addWaypoint(\Oc\CoreBundle\Entity\GeoCacheWaypoint $waypoint)
    {
        $this->waypoints[] = $waypoint;
        return $this;
    }

    /**
     * Remove waypoints
     *
     * @param \Oc\CoreBundle\Entity\GeoCacheWaypoint $waypoint
     */
    public function removeWaypoint(\Oc\CoreBundle\Entity\GeoCacheWaypoint $waypoint)
    {
        $this->waypoints->removeElement($waypoint);
    }

    /**
     * Get waypoints
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWaypoints()
    {
        return $this->waypoints;
    }

    /**
     * Add logs
     *
     * @param \Oc\CoreBundle\Entity\GeoCacheLog $logs
     * @return GeoCache
     */
    public function addLog(\Oc\CoreBundle\Entity\GeoCacheLog $logs)
    {
        $this->logs[] = $logs;

        return $this;
    }

    /**
     * Remove logs
     *
     * @param \Oc\CoreBundle\Entity\GeoCacheLog $logs
     */
    public function removeLog(\Oc\CoreBundle\Entity\GeoCacheLog $logs)
    {
        $this->logs->removeElement($logs);
    }

    /**
     * Get logs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * Set source
     *
     * @param integer $source
     * @return GeoCache
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return integer 
     */
    public function getSource()
    {
        return $this->source;
    }


    /** @ORM\PostLoad */
    public function doStuffOnPostLoad()
    {
        $this->coordinates = new \Oc\CoreBundle\Coordinates(array('latitude' => $this->latitude, 'longitude' => $this->longitude));
    }
}
