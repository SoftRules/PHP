<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use SoftRules\PHP\HtmlRenderer;
use SoftRules\PHP\UI\UIClass;

if (isset($_POST['data'])) {
    $json = json_decode($_POST['data'], true);
    if (isset($json['XML'])) {

        $UIClass = new UIClass();
        $xml = new DOMDocument();
        $xml->loadXML($json['XML']);
        $UIClass->ParseUIXML($xml);

        header('Content-Type: text/html; charset=utf-8');
        exit(new HtmlRenderer($UIClass));
    }
}

http_response_code(204);
