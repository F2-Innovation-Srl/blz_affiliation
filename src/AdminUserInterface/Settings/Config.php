<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

class Config {

    /**
     * Istanza unica del singleton
     * @var object
     */
    private static $instance;
    public  $pages;
    public  $plugin_name;
    public  $plugin_slug;
    
    private function __construct() {
        $config = json_decode(file_get_contents(PLUGIN_PATH.'config.json'), true);
        $this->plugin_name = $config["plugin_name"];
        $this->plugin_slug = $config["plugin_slug"];
        $this->pages = array_map(function($page){ 
                $controller = "BLZ_AFFILIATION\\AdminUserInterface\\Settings\\". $page["controller"];
                $settings = isset($page["settings"]) ? $page["settings"] : null;
                return new Page([
                    "name"       => $page["name"],
                    "slug"       => $page["slug"],
                    "controller" => new $controller($page["slug"],$settings)
                ]);
        }, $config["Pages"]);
    }

    /**
     * Metodo pubblico per l'accesso all'istanza unica di classe.
     * @return object|Config
     */
    public static function loadSettings() {
        if ( !isset(self::$instance) ) {
            self::$instance = new Config();
        }
        return self::$instance;
    }
    


}
