<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use SoftRules\PHP\EvaluateExpressions;
use SoftRules\PHP\UI\SoftRulesFormData;

if (isset($_POST['xml'])) {
    $actions = new EvaluateExpressions(SoftRulesFormData::fromXmlString((string) $_POST['xml']));
    exit($actions->actionList->toJson(JSON_PRETTY_PRINT));
}

http_response_code(204);
