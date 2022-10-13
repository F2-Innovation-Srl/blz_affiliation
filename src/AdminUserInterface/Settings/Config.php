<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Pages\Page;

class Config {

    /**
     * Istanza unica del singleton
     * @var object
     */
    private static $instance;
    private $is_valid;
    public  $pages;
    public  $plugin_name;
    public  $plugin_slug;
    public  $is_affiliation_page;
    
    private function __construct() {

        $roles = wp_roles();
        $role = $roles->role_objects['administrator'];
        $role->add_cap('edit_blz_affiliation');

        $this->is_valid = true;
        $this->is_affiliation_page = "false";
        $config  = json_decode(stripslashes(get_option("blz-affiliation")), true);
        //print_r($config);exit;
        if (empty($config)) {
            $config = json_decode(file_get_contents(PLUGIN_PATH.'config.json'), true);
            $this->is_valid = false;
        }
        
        $this->plugin_name = $config["plugin_name"];
        $this->plugin_slug = $config["plugin_slug"];
        $this->pages = array_map(function($page){ 
                $controller = "BLZ_AFFILIATION\\AdminUserInterface\\Settings\\Pages\\". $page["controller"];
                $settings = isset($page["settings"]) ? $page["settings"] : null;
                return new Page([
                    "name"       => $page["name"],
                    "slug"       => $page["slug"],
                    "controller" => new $controller($this->is_valid,$page["name"],$page["slug"],$settings)
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
