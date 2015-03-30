<?php

namespace Oc\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of geoCache
 *
 * @author Łza
 * @ORM\Entity
 * @ORM\Table(name="geocaches")
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

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=8)
     */
    protected $code;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $cacheName;

    /**
     * @ORM\Column(type="integer")
     */
    protected $cacheType;
    private $datePlaced;
    private $cacheLocation = array();


    /* @var $owner User */
    private $owner;

    /* @var $altitude \lib\Objects\GeoCache\Altitude */
    private $altitude;

	/**
	 * geocache coordinates object (instance of \lib\Objects\Coordinates\Coordinates class)
	 * @var $coordinates \lib\Objects\Coordinates\Coordinates
	 */
	private $coordinates;

    /**
     *
     * @param array $params
     *  'cacheId' => (integer) database cache identifier
     *  'wpId' => (string) geoCache wayPoint (ex. OP21F4)
     */
    public function __construct($params)
    {
//        $this->cacheType = $cacheDbRow['type'];
//        $this->cacheName = $cacheDbRow['name'];
//        $this->code = $cacheDbRow['wp_oc'];
//        $this->datePlaced = strtotime($cacheDbRow['date_hidden']);
//        $this->loadCacheLocation($db);
//		$this->coordinates = new \lib\Objects\Coordinates\Coordinates($cacheDbRow);
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
     * @return \lib\Objects\Coordinates\Coordinates
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

}
