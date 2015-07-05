<?
class rifCore{
	
	private $config;
	private $routing;
	private $hooks;
	private $events;
	private $lng;

    /**
     * Gets the value of config.
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets the value of config.
     *
     * @param mixed $config the config
     *
     * @return self
     */
    public function _setConfig( $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Gets the value of routing.
     *
     * @return mixed
     */
    public function getRouting()
    {
        return $this->routing;
    }

    /**
     * Sets the value of routing.
     *
     * @param mixed $routing the routing
     *
     * @return self
     */
    public function _setRouting(rifRouting $routing)
    {
        $this->routing = $routing;

        return $this;
    }

    /**
     * Gets the value of hooks.
     *
     * @return mixed
     */
    public function getHooks()
    {
        return $this->hooks;
    }

    /**
     * Sets the value of hooks.
     *
     * @param mixed $hooks the hooks
     *
     * @return self
     */
    public function _setHooks(rifHooks $hooks)
    {
        $this->hooks = $hooks;

        return $this;
    }

    /**
     * Gets the value of events.
     *
     * @return mixed
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Sets the value of events.
     *
     * @param mixed $events the events
     *
     * @return self
     */
    public function _setEvents(rifEvent $events)
    {
        $this->events = $events;

        return $this;
    }

    /**
     * Gets the value of lng.
     *
     * @return mixed
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Sets the value of lng.
     *
     * @param mixed $lng the lng
     *
     * @return self
     */
    public function _setLng(rifLng $lng)
    {
        $this->lng = $lng;

        return $this;
    }
}
?>