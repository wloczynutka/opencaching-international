<?php

namespace Oc\CoreBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", length=100, name="origin_uuid")
     */
    protected $originUuid;



    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}