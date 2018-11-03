T5o
================

**T5o** is a very simple PHP localization library.
The meaning of T5o is Tagengo. Tagengo is Japanese. This has the same meaning as localization.


Installation
------------

The recommended way to install T5o is through Composer.

``` shell
composer require hoku-lib/t5o
```


Usage
-----

There are two uses.

Use words with **define**.

``` php
<?php
require_once 'vendor/autoload.php';

$lang = 'fr';
$csvFilePath = dirname(__FILE__) . '/T5o.csv';
\T5o\T5o::define($csvFilePath, $lang);
// \T5o\T5o::define($csvFilePath, $lang, null, true); // auto htmlspecialchars!
?>
<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
    <meta charset="utf-8">
    <title>T5o</title>
</head>
<body>
    <h1><?=SITE_TITLE?></h1>
</body>
</html>
```


Use words with **property**.

``` php
<?php
require_once 'vendor/autoload.php';

$lang = 'fr';
$csvFilePath = dirname(__FILE__) . '/T5o.csv';
$T5o = new \T5o\T5o($csvFilePath);
$T5o->setLang($lang);
// $T5o->setLoggingUndefinedWordsMode(true); // logging!
// $T5o->setAutoHtmlspecialcharsMode(true); // auto htmlspecialchars!
?>
<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
    <meta charset="utf-8">
    <title>T5o</title>
</head>
<body>
    <h1><?=$T5o->SITE_TITLE?></h1>
</body>
</html>
```


CSV Format
----------

* First line:
  * In the leftmost column, enter T5o_LANG.
  * In the second and subsequent columns, enter the language.


* From the second line:
  * Enter a unique identification key in the leftmost column.
  * In the second and subsequent columns, enter a word for each language.


``` txt
T5o_LANG,en,ja,fr
HELLO,Hello,こんにちは,Bonjour
NAME,Name,名前,Nom
SITE_TITLE,T5o bulletin board,T5o掲示板,Tableau d'affichage T5o
```


Logging undefined words
-----------------------

* First line:
  * In the leftmost column, enter T5o_LANG.
  * In the second and subsequent columns, enter the language.


* From the second line:
  * Enter a unique identification key in the leftmost column.
  * In the second and subsequent columns, enter a word for each language.


``` php
$T5o = new T5o('mydir/t5o_csvfile.csv');
$T5o->setLang('en');

// If an undefined word is accessed, log is output.
// Log file name is "CSV_FILE_NAME + .undefined.log".
// ex. mydir/t5o_csvfile.csv.undefined.log
$T5o->setLoggingUndefinedWordsMode(true);
```


License
-------

T5o is released under the MIT License. See the LICENSE
file for details.
