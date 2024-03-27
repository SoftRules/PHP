<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use SoftRules\PHP\Services\SoftRules;

if (isset($_POST['data'])) {
    $json = json_decode($_POST['data'], true);
    if (isset($json['ID'], $json['XML'])) {

        header('Content-Type: text/xml');

        $xml = (new SoftRules())->previousPage($json['ID'], $json['XML']);

        exit($xml->saveHTML($xml->documentElement));
    }
}

http_response_code(204);
