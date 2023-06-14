<?php
/**
 * Very simple localization library.
 *
 * @license   MIT License
 * @author    hoku
 */

namespace T5o;

use \SplFileObject;

class T5o
{
    private $csvFilePath = null;

    private $words   = null;
    private $lang    = null;
    private $default = null;

    private $loggingUndefinedWordsMode = false;
    private $autoHtmlspecialcharsMode = false;


    public static function define(
        $csvFilePath,
        $lang,
        $default = null,
        $autoHtmlspecialcharsMode = false) {

        self::checkCsvFilePath($csvFilePath);
        self::checkLang($lang);
        self::checkDefault($default);

        $csvFile = new SplFileObject($csvFilePath);
        $langs = $csvFile->fgetcsv();
        $targetCol = array_search($lang, $langs);
        if ($targetCol === false) {
            return;
        }

        $csvFile->setFlags(SplFileObject::READ_CSV);
        foreach ($csvFile as $row) {
            if (count($row) > $targetCol) {
                $word = $row[$targetCol];
            } elseif (!is_null($row[0])) {
                if ($default === null) {
                    $word = $row[0];
                } else {
                    $word = $default;
                }
            }

            if (trim($row[0] ?? '') === "") {
                continue;
            }

            if ($autoHtmlspecialcharsMode) {
                define($row[0], htmlspecialchars($word));
            } else {
                define($row[0], $word);
            }
        }
    }

    private static function checkCsvFilePath($csvFilePath) {
        if ($csvFilePath === null) {
            throw new InvalidArgumentException('Argument(csvfile path) is null.');
        }
        if (!file_exists($csvFilePath)) {
            throw new InvalidArgumentException('CSV file does not exist.');
        }
    }

    private static function checkLang($lang) {
        if (gettype($lang) !== 'string') {
            throw new InvalidArgumentException('Argument is not string.');
        }
    }

    private static function checkDefault($default) {
        if ($default !== null && gettype($default) !== 'string') {
            throw new InvalidArgumentException('Argument is not string.');
        }
    }


    function __construct($csvFilePath) {
        self::checkCsvFilePath($csvFilePath);
        $this->csvFilePath = $csvFilePath;
    }

    public function __get($key) {
        if ($this->words === null) {
            $this->loadWords();
        }

        if ($this->lang === null) {
            throw new RuntimeException('Please call setLang() first.');
        }

        if (!array_key_exists($this->lang, $this->words) ||
            !array_key_exists($key, $this->words[$this->lang])) {
            if ($this->loggingUndefinedWordsMode) {
                $this->loggingUndefinedWord($this->lang, $key);
            }
            $word = ($this->default === null) ? $key : $this->default;
        } else {
            $word = $this->words[$this->lang][$key];
        }

        return ($this->autoHtmlspecialcharsMode) ? htmlspecialchars($word) : $word;
    }

    public function setLang($lang) {
        self::checkLang($lang);
        $this->lang = $lang;
    }

    public function setDefault($default) {
        self::checkDefault($default);
        $this->default = $default;
    }

    public function setLoggingUndefinedWordsMode($mode) {
        $this->loggingUndefinedWordsMode = (gettype($mode) !== 'boolean') ? false : $mode;
    }

    public function setAutoHtmlspecialcharsMode($mode) {
        $this->autoHtmlspecialcharsMode = (gettype($mode) !== 'boolean') ? false : $mode;
    }

    private function loadWords() {
        $csvFile = new SplFileObject($this->csvFilePath);
        $langs = $csvFile->fgetcsv();

        $langCount = count($langs) - 1;
        $langIndex = [];
        $this->words = [];
        for ($i = 1; $i < count($langs); $i++) {
            $this->words[$langs[$i]] = [];
            $langIndex[$i] = $langs[$i];
        }

        $csvFile->setFlags(SplFileObject::READ_CSV);
        foreach ($csvFile as $row) {
            for ($i = 1; $i < min(count($row), $langCount + 1); $i++) {
                $this->words[$langs[$i]][$row[0]] = $row[$i];
            }
        }
    }

    private function loggingUndefinedWord($lang, $key) {
        $outFilePath = $this->csvFilePath . '.undefined.log';
        $line = date('Y-m-d H:i:s') . ',' . $lang . ',' . $key . "\n";
        $result = file_put_contents($outFilePath, $line, FILE_APPEND | LOCK_EX);
        if ($result === false) {
            throw new RuntimeException('Cannot write to log file. Please check the permissions. ' . $outFilePath);
        }
    }
}
