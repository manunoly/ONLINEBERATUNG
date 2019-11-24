<?php

class Conference
{
    private $name;
    private $time;
    private $schedule;

    /**
     * Constructor for Conference class
     *
     * @param string $name
     * @param string $time
     * @param string $schedule
     */
    function __construct($name, $time, $schedule)
    {
        $this->name = $name;
        $this->time = $time;
        $this->schedule = $schedule;
    }

    /**
     * get the name of the Conference
     *
     * @return string
     */
    function getName(): string
    {
        return $this->name;
    }

    /**
     * get the time of the Conference
     *
     * @return string
     */
    function getTime(): string
    {
        return $this->time;
    }

    /**
     * get the schedule of the Conference
     *
     * @return string
     */
    function getSchedule(): string
    {
        return $this->schedule;
    }

    function jsonSerialize()
    {
        return
            [
                'name' => $this->getName(),
                'time' => $this->getTime(),
                'schedule' => $this->getSchedule()
            ];
    }
}
