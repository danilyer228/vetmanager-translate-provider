<?php

require_once '_loader.php';

$files = scanAllDir(Config::$path);
$files = fileNameFilter($files, Config::$fileNameFilter);

$strings = array_keys(include('dict.php'));

foreach ($files as $file) {
    if(!is_dir($file)) {
        $content = file_get_contents($file);
        foreach ($strings as $string) {
            $content = str_replace(Config::$quote . $string . Config::$quote, sprintf(Config::getTemplate(), $string), $content);
        }
        file_put_contents($file, $content);
    }
}
