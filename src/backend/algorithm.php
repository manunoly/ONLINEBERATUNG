<?php
class Algorithm
{
    private $data;

    public function __construct()
    { }

    /**
     * function to get solution
     *
     * @param array $list
     * @param array $data
     * @param array $session
     * @param int $timeUsed
     * @param bool $final
     * @return array
     */
    function solve(&$list, &$data, &$session, $timeUsed, $final)
    {
        //TODO: validate input data

        $this->data = $data;
        $timeMin = $session->getTimeMin() * 60;
        $timeMax = $session->getTimeMax() * 60;
        $newUsed = 0;
        if ($timeUsed > 0)
        {
            $timeMin = 0;
            $timeMax -= $timeUsed;
        }
        $solution = $this->solveRecursive($this->data, [], 0, $timeMin, $timeMax, $final);

        $restData = [];
        foreach ($data as $confCant) {
            foreach ($solution as $item) {
                if ($item['time'] == $confCant['time']) {
                    $confCant['cant'] -= $item['cant'];
                    break;
                }
            }
            if ($confCant['cant'] > 0)
                $restData[] = $confCant;
        }
        $data = $restData;

        foreach ($solution as $item) {
            for ($i = 0; $i < $item['cant']; $i++) {
                $timeConf = date("h:iA", strtotime(date($session->getStartHour().":00").'+'.$timeUsed.' minutes'));
                $timeUsed += $item['time'];
                $newUsed += $item['time'];
                $conference = new Conference($list[$item['time']][0], $item['time'], $timeConf);
                $list[$item['time']] = array_slice($list[$item['time']], 1);
                $session->AddConferences($conference);
            }
        }
        return $newUsed;
    }

    /**
     * Recursive function to find solution
     *
     * @param array $data
     * @param array $solution
     * @param int $timeSolve
     * @param int $timeMin
     * @param int $timeMax
     * @param bool $final
     * @return array
     */
    private function solveRecursive($data, $solution, $timeSolve, $timeMin, $timeMax, $final): array
    {
        for ($i = 0; $i < count($data); $i++) {

            for ($j = $data[$i]['cant']; $j > 0; $j--) {
                $time = $timeSolve + $j * $data[$i]['time'];

                if ($time <= $timeMax && $time >= $timeMin) {
                    $solution[] = ['time' => $data[$i]['time'], 'cant' => $j];
                    return $solution;
                } elseif ($time < $timeMax) {
                    $dataRest = array_slice($data, $i + 1);
                    $solutionTmp = $solution;
                    $solutionTmp[] = ['time' => $data[$i]['time'], 'cant' =>  $j];
                    $finalResponse = $this->solveRecursive($dataRest, $solutionTmp, $time, $timeMin, $timeMax, $final);

                    if (count($finalResponse) > 0) {
                        $timeSolve = $time;
                        $solution[] = ['time' => $data[$i]['time'], 'cant' => $j];
                        return $finalResponse;
                    }
                    else if ($final && count($dataRest) == 0) {
                        $solution[] = ['time' => $data[$i]['time'], 'cant' => $j];
                        return $solution;
                    }
                }
            }
        }
        return [];
    }
}