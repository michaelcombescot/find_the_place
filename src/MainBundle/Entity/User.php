<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{

    /** @ORM\PrePersist */
    public function prePersist()
    {
        $this->roles = array('ROLE_USER');
    }
    
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    private $score;


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
     * Set score
     *
     * @param integer $score
     * @return User
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }
    /**
     * @var \MainBundle\Entity\Medium
     */
    private $medium;

    /**
     * Set medium
     *
     * @param \MainBundle\Entity\Medium $medium
     * @return User
     */
    public function setMedium(\MainBundle\Entity\Medium $medium = null)
    {
        $this->medium = $medium;

        return $this;
    }

    /**
     * Get medium
     *
     * @return \MainBundle\Entity\Medium 
     */
    public function getMedium()
    {
        return $this->medium;
    }
    /**
     * @var boolean
     */
    /**
     * @var boolean
     */
    private $lead = false;


    /**
     * Set lead
     *
     * @param boolean $lead
     * @return User
     */
    public function setLead($lead)
    {
        $this->lead = $lead;

        return $this;
    }

    /**
     * Get lead
     *
     * @return boolean 
     */
    public function getLead()
    {
        return $this->lead;
    }
}
