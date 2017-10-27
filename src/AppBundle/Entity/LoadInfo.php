<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoadInfo
 *
 * @ORM\Table(name="load_info")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LoadInfoRepository")
 */
class LoadInfo
{

    /**
     * One Load has Many Trips.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Trip", mappedBy="loadInfo")
     */
    private $trip;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="miles", type="integer")
     */
    private $miles;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="startAddress", type="string", length=255, nullable=true)
     */
    private $startAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="endAddress", type="string", length=255, nullable=true)
     */
    private $endAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dueDate", type="datetime", nullable=true)
     */
    private $dueDate;


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
     * Set price
     *
     * @param float $price
     *
     * @return LoadInfo
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set miles
     *
     * @param integer $miles
     *
     * @return LoadInfo
     */
    public function setMiles($miles)
    {
        $this->miles = $miles;

        return $this;
    }

    /**
     * Get miles
     *
     * @return int
     */
    public function getMiles()
    {
        return $this->miles;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return LoadInfo
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set startAddress
     *
     * @param string $startAddress
     *
     * @return LoadInfo
     */
    public function setStartAddress($startAddress)
    {
        $this->startAddress = $startAddress;

        return $this;
    }

    /**
     * Get startAddress
     *
     * @return string
     */
    public function getStartAddress()
    {
        return $this->startAddress;
    }

    /**
     * Set endAddress
     *
     * @param string $endAddress
     *
     * @return LoadInfo
     */
    public function setEndAddress($endAddress)
    {
        $this->endAddress = $endAddress;

        return $this;
    }

    /**
     * Get endAddress
     *
     * @return string
     */
    public function getEndAddress()
    {
        return $this->endAddress;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return LoadInfo
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->trip = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add trip
     *
     * @param \AppBundle\Entity\Trip $trip
     *
     * @return LoadInfo
     */
    public function addTrip(\AppBundle\Entity\Trip $trip)
    {
        $this->trip[] = $trip;

        return $this;
    }

    /**
     * Remove trip
     *
     * @param \AppBundle\Entity\Trip $trip
     */
    public function removeTrip(\AppBundle\Entity\Trip $trip)
    {
        $this->trip->removeElement($trip);
    }

    /**
     * Get trip
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrip()
    {
        return $this->trip;
    }
}
