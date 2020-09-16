<?php

require_once '_loader.php';

$files = scanAllDir(Config::$path);
$files = fileNameFilter($files, Config::$fileNameFilter);

$strings = [];


foreach($files as $file) {
    if(!is_dir($file)) {
        $content = file_get_contents($file);
        $pattern = "/(_t\()?".Config::$quote."(.*?)".Config::$quote."/us";
        preg_match_all($pattern, $content, $matches);
        $strings = array_merge(filter_pcre($matches, Config::$filterStrings), $strings);
    }
}

$strings = array_unique($strings);

var_dump($strings);

createDictionary($strings);



