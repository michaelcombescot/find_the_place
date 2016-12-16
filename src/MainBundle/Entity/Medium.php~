<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Medium
 */
class Medium
{
    public function __toString()
    {
        return strval($this->id);
    }

    /**
     * @var string
     */
    public $file;

/*
* Generated code
*/
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $path;


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
     * Set path
     *
     * @param string $path
     * @return Medium
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    /**
     * @var \MainBundle\Entity\User
     */
    private $user;

    /**
     * Set user
     *
     * @param \MainBundle\Entity\User $user
     * @return Medium
     */
    public function setUser(\MainBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \MainBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
