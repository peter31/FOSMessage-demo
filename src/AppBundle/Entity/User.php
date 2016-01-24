<?php

namespace AppBundle\Entity;

use FOS\Message\Model\PersonInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class User extends BaseUser implements PersonInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
