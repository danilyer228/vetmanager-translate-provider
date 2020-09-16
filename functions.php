<?php

function scanAllDir($dir, &$results = array()) {
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            $results[] = $path;
        } else if ($value != "." && $value != "..") {
            scanAllDir($path, $results);
            $results[] = $path;
        }
    }
    return $results;
}


function contains($str, array $arr)
{
    foreach($arr as $a) {
        if (strpos($str,$a) !== false) return true;
    }
    return false;
}

function fileNameFilter($files, array $matchTitleWords) {
    foreach ($files as $key => $file) {
        if(contains($file, $matchTitleWords)) {
            unset($files[$key]);
        }
    }
    return $files;
}

if (!function_exists('pre')) {
    function pre() {
        $numargs = func_num_args();
        $arguments = func_get_args();

        echo '<pre>';
        for ($i = 0; $i < $numargs; $i++) {
            var_dump($arguments[$i]);
        }
        echo '</pre>';
    }
}

function filter_pcre($matches, $stop_words = []) {
    $result = [];
    foreach ($matches as $match) {
        foreach ($match as $item) {
            $contains_cyrillic = (bool) preg_match('/[А-Яа-яёЁргыутхОщью]/u', $item);
            $contains_quotes = (bool) preg_match('/['.Config::$quote.']/u', $item);
            $contains_stop_words = contains(mb_strtolower($item), $stop_words);
            if($item && $contains_cyrillic && $contains_quotes && !$contains_stop_words) {
                $result[] = $item;
            }
        }
    }
    return $result;
}

function createDictionary($strings, $translate=false) {
    touch("dict.php");
    $pattern = "<?php
    return [
{data}
    ];
    ";
    $paste = "";
    foreach ($strings as $string) {
        if($translate) {
            $paste .= "\t\t" . Config::$quote . $string . Config::$quote . " => ". Config::$quote. translate($string). Config::$quote .", \n";
        } else {
            $paste .= "\t\t" . $string . " => ".Config::$quote .Config::$quote.", \n";
        }
    }
    file_put_contents("dict.php", str_replace("{data}", $paste, $pattern));
}

function translate($query) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://just-translated.p.rapidapi.com/?text=".urlencode($query)."&lang_from=".Config::$translateFrom."&lang_to=".Config::$translateTo,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "x-rapidapi-host: just-translated.p.rapidapi.com",
            "x-rapidapi-key: 67658bdcb4msh019f6a879ed35bbp1d3dcajsnfbdf5ff703db"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        return "";
    } else {
        $result = json_decode($response, true)['text'][0];
        echo $result . "\n";
        return $result;
    }
}

