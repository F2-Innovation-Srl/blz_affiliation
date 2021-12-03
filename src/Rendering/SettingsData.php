<?php

namespace BLZ_AFFILIATION\Rendering;


class SettingsData { 

    private $link_type;
    private $marketpalace;

    
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
        $this->type = $type;
        $this->marketplace = $marketpalace;
    }


    public function getTemplate() {

        return self::$templates[$this->link_type];
    }

    public function getTrackingID() {

        return "TRACKING ID";
    }

    public function getGAEvent() {

        return "GA EVENT";
    }




}
