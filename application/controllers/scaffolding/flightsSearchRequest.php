<?php

class FlightsSearchRequest{
	private $origin;
	private $destination;
	private $departureDate;
	private $returnDate;
	private $type;
	private $cabinClass;
	private $preferredCarrier;
	private $adultCount;
	private $childCount;
	private $infantCount;
	private $seniorCount;
	private $promotionalPlanType;
	private $isDirectFlight;

    /**
     * Gets the value of origin.
     *
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Sets the value of origin.
     *
     * @param mixed $origin the origin
     *
     * @return self
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Gets the value of destination.
     *
     * @return mixed
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Sets the value of destination.
     *
     * @param mixed $destination the destination
     *
     * @return self
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Gets the value of departureDate.
     *
     * @return mixed
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * Sets the value of departureDate.
     *
     * @param mixed $departureDate the departure date
     *
     * @return self
     */
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    /**
     * Gets the value of returnDate.
     *
     * @return mixed
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * Sets the value of returnDate.
     *
     * @param mixed $returnDate the return date
     *
     * @return self
     */
    public function setReturnDate($returnDate)
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    /**
     * Gets the value of type.
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     *
     * @param mixed $type the type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the value of cabinClass.
     *
     * @return mixed
     */
    public function getCabinClass()
    {
        return $this->cabinClass;
    }

    /**
     * Sets the value of cabinClass.
     *
     * @param mixed $cabinClass the cabin class
     *
     * @return self
     */
    public function setCabinClass($cabinClass)
    {
        $this->cabinClass = $cabinClass;

        return $this;
    }

    /**
     * Gets the value of preferredCarrier.
     *
     * @return mixed
     */
    public function getPreferredCarrier()
    {
        return $this->preferredCarrier;
    }

    /**
     * Sets the value of preferredCarrier.
     *
     * @param mixed $preferredCarrier the preferred carrier
     *
     * @return self
     */
    public function setPreferredCarrier($preferredCarrier)
    {
        $this->preferredCarrier = $preferredCarrier;

        return $this;
    }

    /**
     * Gets the value of adultCount.
     *
     * @return mixed
     */
    public function getAdultCount()
    {
        return $this->adultCount;
    }

    /**
     * Sets the value of adultCount.
     *
     * @param mixed $adultCount the adult count
     *
     * @return self
     */
    public function setAdultCount($adultCount)
    {
        $this->adultCount = $adultCount;

        return $this;
    }

    /**
     * Gets the value of childCount.
     *
     * @return mixed
     */
    public function getChildCount()
    {
        return $this->childCount;
    }

    /**
     * Sets the value of childCount.
     *
     * @param mixed $childCount the child count
     *
     * @return self
     */
    public function setChildCount($childCount)
    {
        $this->childCount = $childCount;

        return $this;
    }

    /**
     * Gets the value of infantCount.
     *
     * @return mixed
     */
    public function getInfantCount()
    {
        return $this->infantCount;
    }

    /**
     * Sets the value of infantCount.
     *
     * @param mixed $infantCount the infant count
     *
     * @return self
     */
    public function setInfantCount($infantCount)
    {
        $this->infantCount = $infantCount;

        return $this;
    }

    /**
     * Gets the value of seniorCount.
     *
     * @return mixed
     */
    public function getSeniorCount()
    {
        return $this->seniorCount;
    }

    /**
     * Sets the value of seniorCount.
     *
     * @param mixed $seniorCount the senior count
     *
     * @return self
     */
    public function setSeniorCount($seniorCount)
    {
        $this->seniorCount = $seniorCount;

        return $this;
    }

    /**
     * Gets the value of promotionalPlanType.
     *
     * @return mixed
     */
    public function getPromotionalPlanType()
    {
        return $this->promotionalPlanType;
    }

    /**
     * Sets the value of promotionalPlanType.
     *
     * @param mixed $promotionalPlanType the promotional plan type
     *
     * @return self
     */
    public function setPromotionalPlanType($promotionalPlanType)
    {
        $this->promotionalPlanType = $promotionalPlanType;

        return $this;
    }

    /**
     * Gets the value of isDirectFlight.
     *
     * @return mixed
     */
    public function getIsDirectFlight()
    {
        return $this->isDirectFlight;
    }

    /**
     * Sets the value of isDirectFlight.
     *
     * @param mixed $isDirectFlight the is direct flight
     *
     * @return self
     */
    public function setIsDirectFlight($isDirectFlight)
    {
        $this->isDirectFlight = $isDirectFlight;

        return $this;
    }
}

?>