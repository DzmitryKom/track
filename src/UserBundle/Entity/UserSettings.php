<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSettings
 *
 * @ORM\Table(name="user_settings")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserSettingsRepository")
 */
class UserSettings
{

    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="userSettings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Settings", inversedBy="userSettings")
     * @ORM\JoinColumn(name="settings_id", referencedColumnName="id", nullable=false)
     */
    private $settings;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return UserSettings
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return UserSettings
     */
    public function setUser(\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set settings
     *
     * @param \UserBundle\Entity\Settings $settings
     *
     * @return UserSettings
     */
    public function setSettings(\UserBundle\Entity\Settings $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Get settings
     *
     * @return \UserBundle\Entity\Settings
     */
    public function getSettings()
    {
        return $this->settings;
    }
}
