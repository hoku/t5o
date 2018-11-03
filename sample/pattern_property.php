<?php
// ini_set('display_errors', 1);
require_once(dirname(dirname(__FILE__)) . '/src/T5o/T5o.php');

$lang = 'fr';
$csvFilePath = dirname(__FILE__) . '/T5o.csv';
$T5o = new \T5o\T5o($csvFilePath);
$T5o->setLang($lang);
$T5o->setLoggingUndefinedWordsMode(true);
$T5o->setAutoHtmlspecialcharsMode(true); // auto htmlspecialchars!
?>
<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
    <meta charset="utf-8">
    <title>T5o sample</title>
</head>
<body>
    <ul>
        <li>HELLO = <?=$T5o->HELLO?></li>
        <li>NAME = <?=$T5o->NAME?></li>
        <li>SITE_TITLE = <?=$T5o->SITE_TITLE?></li>
        <li>TEST_A = <?=$T5o->TEST_A?></li>
        <li>TEST_B = <?=$T5o->TEST_B?></li>
        <li>TEST_C = <?=$T5o->TEST_C?></li>
    </ul>
</body>
</html>
