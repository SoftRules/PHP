<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use GuzzleHttp\Utils;
use SoftRules\PHP\Services\SoftRules;

if (isset($_POST['data'])) {
    $json = Utils::jsonDecode($_POST['data'], true);

    if (isset($json['ID'], $json['XML'], $json['product'])) {
        header('Content-Type: text/xml');

        $xml = (new SoftRules($json['product']))->updateUserinterface($json['ID'], $json['XML']);

        exit($xml->saveHTML($xml->documentElement));
    }
}

http_response_code(204);
