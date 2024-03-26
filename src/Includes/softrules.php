<?php

require_once("Includes/settings.php");

function getSessionID() {
    $url = $GLOBALS['URI'] . "/getsession?username=".$GLOBALS['username']."&password=".$GLOBALS['password'];
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    ));

    $response = curl_exec($curl);

    if(curl_errno($curl)){
    echo 'Curl error: ' . curl_error($curl);
    }
    
    $xml = simplexml_load_string($response);

    curl_close($curl);
    return $xml->Session->SessionID;
}

function Firstpage()
{
    $sessionID = getSessionID();

    $url = $GLOBALS['URI'] . "/userinterface/". $GLOBALS['product'] . "/firstpage/".$sessionID;

    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTPHEADER => array('Content-Type: application/xml'),
    //leeg. Hier kunnen relatiegegevens en polisnummers in om evt. op te halen in de backoffice
    CURLOPT_POSTFIELDS =>'<SR xmlns:xs="http://www.w3.org/2001/XMLSchema">
	<ActionID>nieuw</ActionID>
	<Relatiedocument>
		<Relatiemantel>
			<VP>          
            <VP_ANAAM>Hans</VP_ANAAM>
            <VP_VOORL>J.P.</VP_VOORL>
            <VP_GESLACH>M</VP_GESLACH>
            <VP_STRAAT>Frederik van Eedenstraat</VP_STRAAT>
            <VP_HUISNR>21</VP_HUISNR>
            <VP_TOEVOEG></VP_TOEVOEG>
            <VP_PLAATS>SOMMELSDYK</VP_PLAATS>
            <VP_PCODE>3245RL</VP_PCODE>
            <VP_LAND>NL</VP_LAND>
            <VP_TELNUM>06-12345678</VP_TELNUM>
            <VP_EMAIL>hans@bienefelt.nl</VP_EMAIL>
            <VP_GEBDAT>19690326</VP_GEBDAT>
            <VP_GSSC>I</VP_GSSC>				
			</VP>
		</Relatiemantel>
		<Pakket>
			<Mantel>
				<PK>
					<PK_NUMMER/>
					<PK_EXTERN/>
					<PK_OFFERTE/>
					<PK_PRODUCC/>
				</PK>
			</Mantel>
			<Onderdeel>
				<PP>
					<PP_PRODCFG></PP_PRODCFG>
					<PP_NUMMER></PP_NUMMER>
					<PP_EXTERN></PP_EXTERN>
					<PP_OFFERTE></PP_OFFERTE>
					<PP_PRODUCC/>
				</PP>
			</Onderdeel>
		</Pakket>
	</Relatiedocument>
</SR>', 
    ));

    $response = curl_exec($curl);

    if(curl_errno($curl)){
    echo 'Curl error: ' . curl_error($curl);
    }   

    $xml = new DOMDocument();
    $xml->loadXML($response);

    curl_close($curl);  
    
    return $xml;    
}

function UpdateUserinterface($ID, $XML)
{
    $sessionID = getSessionID();

    $url = $GLOBALS['URI'] . "/UpdateUserinterface/". $ID. "/".$sessionID;
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTPHEADER => array('Content-Type: application/xml'),
    CURLOPT_POSTFIELDS =>$XML, 
    ));

    $response = curl_exec($curl);

    if(curl_errno($curl)){
        echo 'Curl error: ' . curl_error($curl);
    }   

    $xml = new DOMDocument();
    $xml->loadXML($response);

    curl_close($curl);  
    
    return $xml;    
}

function NextPage($ID, $XML)
{
    $sessionID = getSessionID();

    $url = $GLOBALS['URI'] . "/Userinterface/". $GLOBALS['product'] . "/nextpage/".$sessionID;
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTPHEADER => array('Content-Type: application/xml'),
    CURLOPT_POSTFIELDS =>$XML, 
    ));

    $response = curl_exec($curl);

    if(curl_errno($curl)){
        echo 'Curl error: ' . curl_error($curl);
    }   

    $xml = new DOMDocument();
    $xml->loadXML($response);

    curl_close($curl);  
    
    return $xml;    
}

function PreviousPage($ID, $XML)
{
    $sessionID = getSessionID();

    $url = $GLOBALS['URI'] . "/Userinterface/". $GLOBALS['product'] . "/previouspage/".$sessionID;
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTPHEADER => array('Content-Type: application/xml'),
    CURLOPT_POSTFIELDS =>$XML, 
    ));

    $response = curl_exec($curl);

    if(curl_errno($curl)){
        echo 'Curl error: ' . curl_error($curl);
    }   

    $xml = new DOMDocument();
    $xml->loadXML($response);

    curl_close($curl);  
    
    return $xml;    
}