<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Config;

class SettingsData { 

    private $postData;
    private $link_type;
    private $marketpalace;
    private $option_name;

    
    private $templates = [
        
        'affiliate_link' => <<<HTML

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
        $this->link_type = $link_type;
        $this->marketplace = $marketpalace;

        $this->option_name = CONFIG["Items"][0]["suffix"]."-".$marketpalace."-".$link_type;
    }


    public function getTemplate() {

        return $this->templates[$this->link_type];
    }

    public function getTrackingID() {

        return "TRACKING ID";
    }

    public function getGAEvent() {

        return "GA EVENT";
    }




}
