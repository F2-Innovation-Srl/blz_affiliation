<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Config;

class SettingsData { 

    private $postData;
    private $marketpalace;
    private $config;
    private $link_type;


    private $templates = [
        
        'linkButton' => <<<HTML

            <a href="{{ url }}" data-vars-affiliate="{{ ga_event }}" 
               class="affiliation-intext" target="_blank" rel="sponsored"
            >{{ content }}</a>
        HTML,

    ];

    public function __construct($postData,$link_type,$request) {
        $this->postData = $postData;
        $this->link_type = Config::findbySuffix(CONFIG["Items"][0]["settings"]["tabs"],$link_type);
  
        $this->marketplace = Config::findbySuffix($this->link_type["marketplaces"],$request->getMarketplaceKey());
        $settings = get_option( "blz-affiliation-settings" );
        $this->config = [
            "settings" => get_option(CONFIG["Items"][0]["suffix"]."-".$this->marketplace["suffix"]."-".$this->link_type["suffix"]."_settings"),
            "activation_table" => get_option(CONFIG["Items"][0]["suffix"]."-".$this->marketplace["suffix"]."-".$this->link_type["suffix"]),
            "ga_event_template" =>  $settings["ga_event_template"],
            "tracking_id_template" =>  $settings["tracking_id"],
            
        ];
    }


    public function getTemplate() {
    
        return $this->templates[$this->link_type["suffix"]];
    }



    public function getTrackingID() {
        
        $track_id = $this->config["settings"]["trk_default"];
    
        if (isset($this->config["activation_table"][0])) {
            $track_id = $this->config["tracking_id_template"];
            // rimuovo amp da template se non sono una pagina amp
            if ($this->postData->is_amp == false) $track_id = str_replace(["-{amp}","{amp}"],"",$track_id);
            // aggiungo website
            $track_id = str_replace("{website}",$this->config["settings"]["website_trk"],$track_id);
            // sostituisco il restante in base alle regole
            foreach($this->config["activation_table"] as $activation_table)
                if ($this->isValidRule($activation_table) && !empty($activation_table["ga_label"]))
                    $track_id = str_replace("{".$activation_table["trk_label"]."}",$activation_table["trk_val"],$track_id);
            // sostituisco Marketplace con un default se non è stato settato
            $track_id = str_replace("{marketplace}",$this->marketplace["suffix"],$track_id);
            // sostituisco Author con un default se non è stato settato
            $track_id = str_replace("{author}",$this->postData->author["name"],$track_id);
            // rimuovi label non impostate
            $track_id = $this->removeLabels($track_id);
        }
        return $track_id;
        
    }

    public function getGAEvent() {
        $ga_event = $this->config["settings"]["ga_default"];

        if (isset($this->config["activation_table"][0])) {
            $ga_event = $this->config["ga_event_template"];
            // rimuovo amp da template se non sono una pagina amp
            if ($this->postData->is_amp == false) $ga_event = str_replace(["-{amp}","{amp}"],"",$ga_event);
            // aggiungo website
            $ga_event = str_replace("{website}",$this->config["settings"]["website_ga"],$ga_event);
            // sostituisco il restante in base alle regole
            foreach($this->config["activation_table"] as $activation_table)
                if ($this->isValidRule($activation_table) && !empty($activation_table["ga_label"]))
                    $ga_event = str_replace("{".$activation_table["ga_label"]."}",$activation_table["ga_val"],$ga_event);
            // sostituisco Marketplace con un default se non è stato settato
            $ga_event = str_replace("{marketplace}",$this->marketplace["suffix"],$ga_event);
            // sostituisco Author con un default se non è stato settato
            $ga_event = str_replace("{author}",$this->postData->author["name"],$ga_event);
            // rimuovi label non impostate
            $ga_event = $this->removeLabels($ga_event);
        }
        return $ga_event;
    }


    private function isValidRule($activation_table) {
        switch ($activation_table["attivatore"]) {
            case "POSTTYPE":
                return ($activation_table["regola"] == $this->postData->post_type) ? true : false;
                break;
            case "USERS":
                return ($activation_table["regola"] == $this->postData->author["id"]) ? true : false;
                break;
            default:
                return (in_array($activation_table["regola"],$this->postData->taxonomies[$activation_table["attivatore"]])) ? true : false;
                break;
        } 
    }

    private function removeLabels($str) {
        $str_temp = $str;
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($str, "{", $startFrom))) {
          $contentStart += strlen("{");
          $contentEnd = strpos($str, "}", $contentStart);
          if (false === $contentEnd)  break;
          $str_temp = str_replace("{".substr($str, $contentStart, $contentEnd - $contentStart). "}","",$str_temp);
          $str_temp = str_replace("--","-",$str_temp);
          $startFrom = $contentEnd + strlen("}");
        }
        return $str_temp;
    }


}
