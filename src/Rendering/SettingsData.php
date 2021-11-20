<?php

namespace BLZ_AFFILIATION\Rendering;


class SettingsData { 

    static $templates = [
        
        'affiliate_link' => <<<HTML

            <a href="{{ url }}" data-vars-affiliate="{{ ga_event }}" 
               class="affiliation-intext" target="_blank" rel="sponsored"
            >{{ content }}</a>
        HTML,

        'ga_event' => <<<EVT
            mtz cta {{ website }} {{ category }} editorial {{ author }} {{ marketplace }}
        EVT
    ];

    
    public static function getTemplate($template) {

        return self::$templates[$template];
    }




}
