<?php

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');

ini_set('set_time_limit', 300);
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 43200);

require dirname(__DIR__, 1) . '/src/algorithms.php';

$dir = dirname(__DIR__, 1) . '/storage/';
$algorithms = new \Encrypted\Algorithms();
$directories = scandir($dir);
foreach ($directories as $key => $value) {
    if ($value == 'encrypt.txt') {
        $file = file($dir . $directories[$key], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!empty($file)) {
            $encrypt = array();
            foreach ($file as $text) {
                $encrypt[] = $algorithms->encrypt($text);
            }
            
            $file = $dir . 'encrypt_' . time();
            file_put_contents($file . '.txt', implode(PHP_EOL, $encrypt), FILE_APPEND | LOCK_EX);
        }
    }
}
