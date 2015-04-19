<?php

namespace Oc\CoreBundle\Entity;

 use Doctrine\ORM\Mapping as ORM;

/**
 * Description of GeoCacheLog
 * 1 	Found it 	log/16x16-found.png
 * 2 	Didn't find it 	log/16x16-dnf.png
 * 3 	Comment 	log/16x16-note.png
 * 4 	Moved 	log/16x16-moved.png
 * 5 	Potrzebny serwis 	Needs maintenance 	log/16x16-need-maintenance.png
 * 7 	Attended 	log/16x16-attend.png
 * 8 	Zamierza uczestniczyć 	Will attend 	log/16x16-will_attend.png
 * 10 	Gotowa do szukania 	Ready to search 	log/16x16-published.png
 * 11 	Niedostępna czasowo 	Temporarily unavailable 	log/16x16-temporary.png
 * 12 	Komentarz COG 	OC Team comment 	log/16x16-octeam.png
 * 9 	Zarchiwizowana 	Archived 	log/16x16-trash.png
 *
 * @ORM\Entity
 * @ORM\Table(name="geocaches_logs")
 *
 * @author Łza
 */
class GeoCacheLog
{

    const LOGTYPE_FOUNDIT = 1;
    const LOGTYPE_DIDNOTFIND = 2;
    const LOGTYPE_COMMENT = 3;
    const LOGTYPE_MOVED = 4;
    const LOGTYPE_NEEDMAINTENANCE = 5;
    const LOGTYPE_ARCHIVED = 6;
    const LOGTYPE_ATTENDED = 7;
    const LOGTYPE_DEACTIVATED = 8;
    const LOGTYPE_SERVICED = 9;
    const LOGTYPE_ACTIVATED = 10;
    const LOGTYPE_WILLATTEND = 11;
    const LOGTYPE_OCTEAMCOMMENT = 12;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="GeoCache", inversedBy="logs")
     * @ORM\JoinColumn(name="geocache_id", referencedColumnName="id")
     */
    protected $geoCache;

    /**
     * @ORM\Column(type="guid", name="uuid", unique=true)
     */
    protected $uuid;

    /**
     * @ORM\Column(type="datetime", name="datetime")
     */
    protected $dateTime;

    /**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="logsEntered")
     * @ORM\JoinColumn(name="owner", referencedColumnName="id")
	 * @var $author User
	 */
    protected $author;

	/**
     * @ORM\Column(type="integer")
	 * @var integer
	 */
	protected $type;

	/**
	 * @ORM\Column(type="boolean")
	 * @var boolean
	 */
	protected $recommendation;

    /**
     * @ORM\Column(type="text")
     */
	protected $text;

    /**
     * @ORM\Column(type="datetime", name="sync_datetime")
     */
	protected $syncDatetime;


    public function __construct()
    {
        $this->syncDatetime = new DateTime();
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
     * Set uuid
     *
     * @param guid $uuid
     * @return GeoCacheLog
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
     * Set dateTime
     *
     * @param \DateTime $dateTime
     * @return GeoCacheLog
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime 
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return GeoCacheLog
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
     * Set recommendation
     *
     * @param boolean $recommendation
     * @return GeoCacheLog
     */
    public function setRecommendation($recommendation)
    {
        $this->recommendation = $recommendation;
        return $this;
    }

    /**
     * Get recommendation
     *
     * @return boolean 
     */
    public function getRecommendation()
    {
        return $this->recommendation;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return GeoCacheLog
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set geoCache
     *
     * @param \Oc\CoreBundle\Entity\GeoCache $geoCache
     * @return GeoCacheLog
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
     * Set author
     *
     * @param \Oc\CoreBundle\Entity\User $author
     * @return GeoCacheLog
     */
    public function setAuthor(\Oc\CoreBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Oc\CoreBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set syncDatetime
     *
     * @param \DateTime $syncDatetime
     * @return GeoCacheLog
     */
    public function setSyncDatetime($syncDatetime)
    {
        $this->syncDatetime = $syncDatetime;

        return $this;
    }

    /**
     * Get syncDatetime
     *
     * @return \DateTime 
     */
    public function getSyncDatetime()
    {
        return $this->syncDatetime;
    }
}
