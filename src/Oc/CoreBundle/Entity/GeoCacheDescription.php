<?php

namespace Oc\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="geocaches_descriptions")
 */
class GeoCacheDescription
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="GeoCache", inversedBy="descriptions")
     * @ORM\JoinColumn(name="geocache_id", referencedColumnName="id")
     */
    protected $geoCacheId;

    /**
     * @ORM\Column(type="string", length=2)
     */
	protected $language;

    /**
     * @ORM\Column(name="text_place", type="text")
     */
	protected $text;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
	protected $hint;

    /**
     * @ORM\Column(name="text_geocache", type="text", nullable=true)
     * for description about how geocache is hidden
     */
    protected $textGeocache;

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
     * Set language
     *
     * @param string $language
     * @return GeoCacheDescription
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return GeoCacheDescription
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
     * Set geoCacheId
     *
     * @param \Oc\CoreBundle\Entity\GeoCache $geoCacheId
     * @return GeoCacheDescription
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
     * Set hint
     *
     * @param string $hint
     * @return GeoCacheDescription
     */
    public function setHint($hint)
    {
        $this->hint = $hint;

        return $this;
    }

    /**
     * Get hint
     *
     * @return string 
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * Set textGeocache
     *
     * @param string $textGeocache
     * @return GeoCacheDescription
     */
    public function setTextGeocache($textGeocache)
    {
        $this->textGeocache = $textGeocache;

        return $this;
    }

    /**
     * Get textGeocache
     *
     * @return string 
     */
    public function getTextGeocache()
    {
        return $this->textGeocache;
    }
}
