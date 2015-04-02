<?php

namespace Oc\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="images")
 */
class Image
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="GeoCache", inversedBy="images")
     * @ORM\JoinColumn(name="geocache_id", referencedColumnName="id")
     */
    protected $geoCacheId;

    /**
     * @ORM\Column(type="guid")
     */
	protected $uuid;

    /**
     * @ORM\Column(type="string")
     */
	protected $url;

    /**
     * @ORM\Column(type="string")
     */
	protected $thumbUrl;

    /**
     * @ORM\Column(type="string")
     */
	protected $caption;

    /**
     * @ORM\Column(type="boolean", name="is_spoiler")
     */
	protected $isSpoiler;



    /**
     * Set uuid
     *
     * @param guid $uuid
     * @return Image
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
     * Set url
     *
     * @param string $url
     * @return Image
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
     * Set thumbUrl
     *
     * @param string $thumbUrl
     * @return Image
     */
    public function setThumbUrl($thumbUrl)
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
    }

    /**
     * Get thumbUrl
     *
     * @return string 
     */
    public function getThumbUrl()
    {
        return $this->thumbUrl;
    }

    /**
     * Set caption
     *
     * @param string $caption
     * @return Image
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string 
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set isSpoiler
     *
     * @param boolean $isSpoiler
     * @return Image
     */
    public function setIsSpoiler($isSpoiler)
    {
        $this->isSpoiler = $isSpoiler;

        return $this;
    }

    /**
     * Get isSpoiler
     *
     * @return boolean 
     */
    public function getIsSpoiler()
    {
        return $this->isSpoiler;
    }

    /**
     * Set geoCacheId
     *
     * @param \Oc\CoreBundle\Entity\GeoCache $geoCacheId
     * @return Image
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

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
