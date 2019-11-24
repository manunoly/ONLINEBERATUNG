<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");

require('./algorithm.php');
require('./model/topic.php');
require('./model/session.php');
require('./model/conference.php');

$response = array();

/**
 * response function
 *
 * @param string $status
 * @param boolean $error
 * @param string $msg
 * @param string opcional $data
 * @return void
 */
function response($status, $error, $msg, $data = '')
{
    $response = array(
        "status" => $status,
        "error" => $error,
        "message" => $msg,
        "data" => $data
    );
    die(json_encode($response));
}
// var_dump($_POST);

if (!$_POST['filename']) {
    return response('error', true, 'Choose a file!');
}

if (!file_exists(getcwd() . $_POST['filename'])) {
    return response('error', true, 'File not found!');
}

$gestor = @fopen(getcwd() . $_POST['filename'], "r");
$tmpDataLine = [];
if ($gestor) {
    while (($line = fgets($gestor)) !== false) {
        if ($line) {
            $time = end(explode(' ', $line));
            $title =   str_replace($time, '', $line);
            $time =  str_replace(array("\n", 'min', "\r", " "), '', $time);

            if (!$time || !ctype_digit($time)) {
                response('error', true, 'The conference time was not found, only integer times are allowed:  ' . $line);
            }

            if (preg_match('/[0-9]/', $title)) {
                response('error', true, "The conference title can't containt numbers:  " . $line);
            }

            $tmpDataLine[] = $time . ' Minutes: ' . $title;
            $data[$time][] = $title;
        }
    }

    if (!feof($gestor)) {
        response('error', true, "File not found");
    }
    fclose($gestor);

    krsort($data);

    $dataCant = [];
    foreach ($data as $key => $value) {
        $dataCant[] = array('time' => $key, 'cant' => count($data[$key]));
    }

    $sesionData = array(array('startHour' => 9, 'endHourStart' => 12, 'endHourEnd' => 12), 
                        array('startHour' => 12, 'endHourStart' => 12, 'endHourEnd' => 12, 'name' => 'LUNCH'), 
                        array('startHour' => 13, 'endHourStart' => 16, 'endHourEnd' => 17), 
                        array('startHour' => 17, 'endHourStart' => 17, 'endHourEnd' => 17, 'name' => 'SOCIAL EVENT'));
    $algorithm = new Algorithm();

    //create topic and session to complete
    $topics;
    $pos = 1;
    $insert = false;
    while (count($dataCant) > 0) {
        $noResults = false;
        while (count($dataCant) > 0) {
            $topic = new Topic('Topic ' . $pos);
            foreach ($sesionData as $sesionD) {
                $sesion = new Session($sesionD['startHour'], $sesionD['endHourStart'], $sesionD['endHourEnd']);
                if ($sesion->getTimeMin() > 0)
                {
                    if ($algorithm->solve($data, $dataCant, $sesion, 0, $insert) == 0)
                    {
                        $noResults = true;
                        break;
                    }
                }
                else
                {
                    $timeConf = date("h:iA", strtotime(date($sesionD['startHour'].":00")));
                    $sesion->AddConferences(new Conference($sesionD['name'], '', $timeConf));
                }
                $topic->addSession($sesion);
                if (count($dataCant) == 0)
                    break;
            }
            if ($noResults)
                break;
            $topics[] = $topic;
            $pos++;
        }
        if ($noResults)
        {
            //complete rank sessions
            foreach ($topics as $topic) {
                foreach ($topic->getSessions() as $session) {
                    $used = 0;
                    foreach ($session->getConferences() as $conference) {
                        $used += $conference->getTime();
                    }
                    while ($session->getTimeMax()*60 - $used > 0)
                    {
                        $newUsed += $algorithm->solve($data, $dataCant, $session, $used, $insert);
                        if ($newUsed == 0)
                            break;
                        $used += $newUsed;
                        $newUsed = 0;
                    }
                }
            }
            $insert = true;
        }
    }

    $dataResponse;
    foreach ($topics as $topic) {
        $dataResponse[] = $topic->jsonSerialize();
    }
    $dataResponse = json_encode($dataResponse);
    response("success", false, "File Processed Successfully", $dataResponse);
} else {
    $response = array(
        "status" => "error",
        "error" => true,
        "message" => "Error reading file!"
    );
    echo json_encode($response);
    return;
}
