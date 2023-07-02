<?php
$app_name = "DISPENSASI SISWA";
$dev = "development";
$app_url = null;

function base_url(){
    global $dev,$app_url;
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else if($dev != "local"){
        $protocol = 'https';
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $app_url != null ? $app_url : $_SERVER['HTTP_HOST'];
}