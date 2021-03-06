<?php

namespace Oc\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="geocaches_waypoint_types")
 */
class GeoCacheWaypointType
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $typeShort;

    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $symbol;

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
     * Set typeShort
     *
     * @param string $typeShort
     * @return GeoCacheWaypointType
     */
    public function setTypeShort($typeShort)
    {
        $this->typeShort = $typeShort;

        return $this;
    }

    /**
     * Get typeShort
     *
     * @return string 
     */
    public function getTypeShort()
    {
        return $this->typeShort;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return GeoCacheWaypointType
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
     * Set symbol
     *
     * @param string $symbol
     * @return GeoCacheWaypointType
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get symbol
     *
     * @return string 
     */
    public function getSymbol()
    {
        return $this->symbol;
    }
}
