<?php

require_once '_loader.php';

$strings = array_keys(include('dict.php'));

createDictionary($strings, true);