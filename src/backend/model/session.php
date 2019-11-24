<?php
//require('./conference.php');

class Session
{
    private $startHour;
    private $endHourStart;
    private $endHourEnd;
    private $staticEnd;
    private $timeMin;
    private $timeMax;
    private $conferences = [];

    /**
     * Session constructor
     *
     * @param int $startHour
     * @param int $endHourStart
     * @param int $endHourEnd
     */
    function __construct($startHour, $endHourStart, $endHourEnd = null)
    {
        $this->startHour = $startHour;
        $this->endHourStart = $endHourStart;
        $this->endHourEnd = $endHourEnd;

        if ($endHourStart == $endHourEnd)
            $this->staticEnd = true;
        else
            $this->staticEnd = false;

        //TODO: validate time for incomplete hours or partial
        $start = (int) $startHour;
        $endS = (int) $endHourStart;

        if ($this->staticEnd) {
            $this->timeMin = $endS - $start;
            $this->timeMax = $this->timeMin;
        } else {
            $endE = (int) $endHourEnd;
            $this->timeMin = $endS - $start;
            $this->timeMax = $endE - $start;
        }
    }

    /**
     * return conferences for this session
     *
     * @return array
     */
    function getConferences(): array
    {
        return $this->conferences;
    }

    /**
     * return TimeMin for this session
     *
     * @return int
     */
    function getTimeMin()
    {
        return $this->timeMin;
    }

    /**
     * return TimeMax for this session
     *
     * @return int
     */
    function getTimeMax()
    {
        return $this->timeMax;
    }

    /**
     * return TimeMax for this session
     *
     * @return int
     */
    function getStartHour()
    {
        return $this->startHour;
    }

    /**
     * add conference to session
     *
     * @param Conference object
     * @return void
     */
    function AddConferences(Conference $conf)
    {
        $this->conferences[] = $conf;
    }

    function jsonSerialize()
    {
        if (count($this->conferences) > 0) {
            $arrayConf = [];
            foreach ($this->conferences as $conf) {
                $arrayConf[] = $conf->jsonSerialize();
            }
        }

        return
            [
                'startHour'   => $this->startHour,
                'endHourStart' => $this->endHourStart,
                'endHourEnd' => $this->endHourEnd,
                'conferences' => $arrayConf
            ];
    }
}
