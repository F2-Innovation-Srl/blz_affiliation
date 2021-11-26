<?php
namespace BLZ_AFFILIATION\Utils;

class Config {

    /**
     * Carica le Config da Json
     */
    public static function loadSettings(){
        define('CONFIG', json_decode(file_get_contents(PLUGIN_PATH.'config.json'), true));
    } 

    /**
     * Cerca nel config tramite chiave
     */
    public static function findbySuffix($obj,$val){
        return $obj[array_search($val, array_column($obj, 'suffix'))];
    } 

    /**
     * Torna l'api name dal settings
     */
    public static function getApiSlug($marketplace){
        $settings = Config::findbySuffix(CONFIG["Items"],CONFIG["plugin_suffix"])["settings"];
        return  Config::findbySuffix($settings["marketplaces"],$marketplace)["api_slug"];
    }
   

}
