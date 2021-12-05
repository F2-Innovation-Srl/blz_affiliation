<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Config;

class SettingsData { 

    private $postData;
    private $marketpalace;
    private $config;


    private $templates = [
        
        'linkButton' => <<<HTML

            <a href="{{ url }}" data-vars-affiliate="{{ ga_event }}" 
               class="affiliation-intext" target="_blank" rel="sponsored"
            >{{ content }}</a>
        HTML,

        'ga_event' => <<<EVT
            mtz cta {{ website }} {{ category }} editorial {{ author }} {{ marketplace }}
        EVT
    ];

    public function __construct($postData,$link_type,$marketpalace) {
        $this->postData = $postData;
        $this->marketplace = Config::findbySuffix(CONFIG["Items"][0]["settings"]["marketplaces"],$marketpalace);

        $this->config = [
            "settings" => get_option(CONFIG["Items"][0]["suffix"]."-".$marketpalace."-".$link_type."_settings"),
            "activation_table" => get_option(CONFIG["Items"][0]["suffix"]."-".$marketpalace."-".$link_type),
            "ga_event_template" =>  $this->marketplace["ga_event_template"],
            "tracking_id" =>  $this->marketplace["tracking_id"],
            
        ];
    }


    public function getTemplate() {

        return $this->templates[$this->link_type];
    }

    public function getTrackingID() {
        //echo "<pre>";
        //print_r($this->config);
        //print_r($this->postData);
        foreach($this->config["activation_table"] as $activation_table){

        }
        exit;

        return "TRACKING ID";
    }

    public function getGAEvent() {

        return "GA EVENT";
    }




}
