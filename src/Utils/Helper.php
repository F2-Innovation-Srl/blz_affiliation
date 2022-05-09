<?php

namespace BLZ_AFFILIATION\Utils;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Config;
class Helper {

    /**
     * Replace special characters and spaces in a given string
     * and return the result lowering capital letters
     */
    static function slugify($text) {

        $text = str_replace('à', 'a', $text);
        $text = str_replace(array('è','é'), 'e', $text);
        $text = str_replace('ì', 'i', $text);
        $text = str_replace('ò', 'o', $text);
        $text = str_replace('ù', 'u', $text);
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    public static function pre($obj,$die)
    {
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
        if ($die) exit;

    }

    /**
     * Cerca nel config tramite chiave
     */
    public static function findbySlug($obj,$val){
        return $obj[array_search($val, array_column($obj, 'slug'))];
    } 

    /**
     * Torna l'api name dal settings
     */
    public static function getApiSlug($marketplace){
        $config = Config::loadSettings();
        return  Helper::findbySlug($config->pages[0]->controller->settings["tabs"][0]["marketplaces"],$marketplace)["api_slug"];
    }

    /**
     * Setta il valore in config che dice se la pagina ha un link affiliato
     */
    public static function isAffiliationPage(){
        $config = Config::loadSettings();
        $config->is_affiliation_page = true; 
    }

     /**
     * Torna la lista dei pattern da verificare
     *
     * @return array
     */
    public static  function getMarketplacePatterns() {

        return [
            'Amazon',
            'Ebay',
            'AmazonShorted', 
            'AmazonPrimeVideo',
            'EbayShorted', 
            'PrettyLink', 
        ];
    }
}
