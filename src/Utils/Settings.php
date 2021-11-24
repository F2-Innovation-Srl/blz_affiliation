<?php
namespace BLZ_AFFILIATION\Utils;

class Settings {

    /**
     * Carica i settings da Json
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
    public static function getApiSlug(){
        $settings = Settings::findbySuffix(CONFIG["Items"],$_GET["page"])["settings"];
        return  Settings::findbySuffix($settings["marketplaces"],$_GET["sub_tab"])["api_slug"];
    }
   

}
