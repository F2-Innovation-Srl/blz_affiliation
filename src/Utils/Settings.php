<?php
namespace BLZ_AFFILIATION\Utils;

class Settings {

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
