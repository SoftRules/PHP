<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/bootstrap.php';
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet"
          href="//cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css"
          integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu"
          crossorigin="anonymous">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoftRules PHP</title>
</head>
<body>
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
    <?php echo \SoftRules\PHP\SoftRulesForm::make('asr.vvp')->withInitialXml(file_get_contents(__DIR__ . '/../examples/first-page.xml')); ?>
    </div>
    <div class="col-xs-2"></div>
</body>
</html>
