<?php
namespace T5o;

use \SplFileObject;

class T5o
{
    private $words   = null;
    private $lang    = null;
    private $default = null;

    private $loggingUndefinedWordsMode = false;

    function __construct($lang, $default = null) {
        if ($lang === null) { return; }

        $csvFile = new SplFileObject(dirname(__FILE__) . '/T5o.csv');
        $langs = $csvFile->fgetcsv();
        $targetCol = array_search($lang, $langs);
        if ($targetCol === false) {
            return;
        }

        $csvFile->setFlags(SplFileObject::READ_CSV);
        foreach ($csvFile as $row) {
            if (count($row) > $targetCol) {
                define($row[0], $row[$targetCol]);
            } elseif (!is_null($row[0])) {
                if ($default === null) {
                    define($row[0], $row[0]);
                } else {
                    define($row[0], $default);
                }
            }
        }
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
            return ($this->default === null) ? $key : $this->default;
        }

        return $this->words[$this->lang][$key];
    }

    public function setLang($lang) {
        if (gettype($lang) !== 'string') {
            throw new InvalidArgumentException('Argument is not string.');
        }
        $this->lang = $lang;
    }

    public function setDefault($default) {
        if ($default !== null && gettype($default) !== 'string') {
            throw new InvalidArgumentException('Argument is not string.');
        }
        $this->default = $default;
    }

    public function setLoggingMode($mode) {
        $this->loggingUndefinedWordsMode = (gettype($mode) !== 'boolean') ? false : $mode;
    }

    private function loadWords() {
        $csvFile = new SplFileObject(dirname(__FILE__) . '/T5o.csv');
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
        $outFilePath = dirname(__FILE__) . '/T5o.log';
        $line = date('Y-m-d H:i:s') . ',' . $lang . ',' . $key . "\n";
        $result = file_put_contents($outFilePath, $line, FILE_APPEND | LOCK_EX);
        if ($result === false) {
            throw new RuntimeException('Cannot write to log file. Please check the permissions. ' . $outFilePath);
        }
    }
}
