<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Pages\Partials;

/**
 * Class GaTrackingIdSettings
 *
 * @package BLZ_AFFILIATION
 */
class Tab {


    private $tabs;
    private $childs;
    private $current;
    private $output =
    <<<HTML
        <div id="icon-themes" class="icon32"><br></div>
        <h2 class="nav-tab-wrapper">{{ tabs }}</h2>
        <div id="icon-themes" class="icon32"><br></div>
        <h2 class="nav-tab-wrapper">{{ childs }}</h2>
    HTML;

	/**
	 * AdminPage constructor.
	 */
    
	function __construct($settings, $current) {
        
        $this->current       =  $current;
        $this->tabs = $settings["tabs"];
        $this->childs = $this->current["tab"]["marketplaces"];
        
        $this->render();

    }


    public function render() {
        $tabs = "";
        $childs = "";
        foreach($this->tabs as $tab) 
            $tabs .= "<a class='nav-tab".(( $tab["slug"] == $this->current["tab"]["slug"] ) ? " nav-tab-active" : "")."' href='?page=".$_GET["page"]."&tab=".$tab["slug"]."&marketplace=".$this->current["marketplace"]["slug"]."'>".$tab["name"]."</a>";
       
        foreach($this->childs as $tab) 
            $childs .= "<a class='nav-tab".(( $tab["slug"] == $this->current["marketplace"]["slug"] ) ? " nav-tab-active" : "")."' href='?page=".$_GET["page"]."&tab=".$this->current["tab"]["slug"]."&marketplace=".$tab["slug"]."'>".$tab["name"]."</a>";
           
        return str_replace( ['{{ tabs }}','{{ childs }}'], [ $tabs, $childs ],  $this->output );
    
    }

    
}