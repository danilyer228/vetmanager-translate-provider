<?php

class Config
{
    public static $path = "../vetmanager-extjs/ui/js/";
    public static $fileNameFilter = ['vendor', "View", "Helper", "GlobalEvents", "Snippet", 'composer', "tests", "Utility", "ImportComp", "Seed", 'lang', 'svg', 'min', "Lang", "LS", "Entity", "LaboratoryPetTypeMapper", "UICon", "BillingData"];
    public static $filterStrings = ["_t(", "+", "/[a-z", "жизни", "<", ">", "%", '\\', "select", "join", "when", "as", "insert", "if", "concat", "update", '$', 'return', '{'];
    public static $quote = "'";
    public static $translateFrom = "ru";
    public static $translateTo = "en";
    public static function getTemplate() {
        return "_t(".self::$quote."%s".self::$quote.")";
    }
}