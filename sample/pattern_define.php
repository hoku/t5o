<?php
// ini_set('display_errors', 1);
require_once(dirname(dirname(__FILE__)) . '/src/T5o/T5o.php');

use \T5o\T5o;

$lang = 'fr';
$csvFilePath = dirname(__FILE__) . '/T5o.csv';

// normal
// new T5o($csvFilePath, $lang);

// auto htmlspecialchars!
new T5o($csvFilePath, $lang, null, true);
?>
<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
    <meta charset="utf-8">
    <title>T5o sample</title>
</head>
<body>
    <ul>
        <li>HELLO = <?=HELLO?></li>
        <li>NAME = <?=NAME?></li>
        <li>SITE_TITLE = <?=SITE_TITLE?></li>
        <li>TEST_A = <?=TEST_A?></li>
        <li>TEST_B = <?=TEST_B?></li>
        <li>TEST_C = <?=TEST_C?></li>
    </ul>
</body>
</html>
