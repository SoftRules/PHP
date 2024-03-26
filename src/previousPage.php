<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Origin: GET, POST');
header('Access-Control-Allow-Headers: *');

if (isset($_POST['data'])) 
{
    $json = json_decode($_POST['data'], true);
    if ((isset($json['ID'])) && (isset($json['XML']))) 
    {
        require_once("Includes/softrules.php");
        require_once("Includes/interfaces.php");
        require_once("Includes/uiclass.php");

        $UIClass = new UIClass();
        $xml = PreviousPage($json['ID'], $json['XML']);
        
        $UIClass->ParseUIXML($xml);

        $str = $xml->saveHTML($xml->documentElement);
        $str = str_replace('"', '\'', $str);
        echo $str;        
    }
}
