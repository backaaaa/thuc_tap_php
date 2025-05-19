<?php
function logAc($action)
{
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'];
    $logMess = "$time - $action - IP: $ip \n";

    $logFile = 'logs/log_' . $date . '.txt';
    if(!file_exists($logFile)){
        fopen($logFile, 'w');
    }

    file_put_contents($logFile, $logMess, FILE_APPEND);

}