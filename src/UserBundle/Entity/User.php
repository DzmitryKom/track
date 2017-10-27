<?php

namespace UserBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
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

    public function __construct()
    {
	    parent::__construct();
	    $this->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
        $this->userSettings = new ArrayCollection();
    }

    /**
     * One User has Many UserSettings.
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\UserSettings", mappedBy="user")
     */
    private $userSettings;


    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getFullName()
    {
        if (!empty($this->firstName) or (! empty($this->lastName))){
            $full_name = $this->firstName.' '.$this->lastName;
        }else{
            $full_name = $this->getUsername();
        }
        return $full_name;
    }


    /**
     * Add userSetting
     *
     * @param \UserBundle\Entity\UserSettings $userSetting
     *
     * @return User
     */
    public function addUserSetting(\UserBundle\Entity\UserSettings $userSetting)
    {
        $this->userSettings[] = $userSetting;

        return $this;
    }

    /**
     * Remove userSetting
     *
     * @param \UserBundle\Entity\UserSettings $userSetting
     */
    public function removeUserSetting(\UserBundle\Entity\UserSettings $userSetting)
    {
        $this->userSettings->removeElement($userSetting);
    }

    /**
     * Get userSettings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserSettings()
    {
        return $this->userSettings;
    }
}
