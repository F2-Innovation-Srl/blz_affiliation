<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Config;

class SettingsData { 

    private $postData;
    private $marketpalace;
    private $config;
    private $link_type;
    private $request;

    private $templates = [
        
        'linkButton' => <<<HTML

            <a href="{{ url }}" data-vars-affiliate="{{ ga_event }}" 
               class="affiliation-intext" target="_blank" rel="sponsored"
            >{{ content }}</a>
        HTML,
        'linkPrograms' => <<<HTML

        <a href="{{ url }}" data-vars-affiliate="{{ ga_event }}" 
           class="affiliation-intext" target="_blank" rel="sponsored"
        >{{ content }}</a>
    HTML,
    ];

    public function __construct($postData,$link_type,$request) {
        $this->postData = $postData;
        $this->request = $request;
        $this->link_type = Config::findbySuffix(CONFIG["Items"][0]["settings"]["tabs"],$link_type);
  
        $this->marketplace = Config::findbySuffix($this->link_type["marketplaces"],$this->request->getMarketplaceKey());
        $global_settings = get_option( "blz-affiliation-settings" );
        $this->config = [
            "global_settings" => $global_settings,
            "settings" => get_option(CONFIG["Items"][0]["suffix"]."-".$this->link_type["suffix"]."-".$this->marketplace["suffix"]."_settings"),
            "activation_table" => get_option(CONFIG["Items"][0]["suffix"]."-".$this->link_type["suffix"]."-".$this->marketplace["suffix"]),
            "ga_event_template" =>  $this->marketplace["ga_event_template"],
            "tracking_id_template" =>  $this->marketplace["tracking_id"],
            
        ];

    }


    public function getTemplate() {
    
        return $this->templates[$this->link_type["suffix"]];
    }



    public function getTrackingID() {
        // Se è stato settato manualmente prendo quello
        if ($this->request->getTrackingId()) return $this->request->getTrackingId();
        
        $track_id = $this->config["tracking_id_template"];

        if (isset($this->config["activation_table"][0])) {
            
            // rimuovo amp da template se non sono una pagina amp
            if ($this->postData->is_amp == false) $track_id = str_replace(["-{amp}","{amp}"],"",$track_id);
            // aggiungo website
            $track_id = str_replace("{website}",$this->config["global_settings"]["website_trk"],$track_id);
            // sostituisco il restante in base alle regole
            foreach(array_reverse($this->config["activation_table"]) as $activation_table)
                if ($this->isValidRule($activation_table) && !empty($activation_table["ga_label"]))
                    $track_id = str_replace("{".$activation_table["trk_label"]."}",$activation_table["trk_val"],$track_id);
            
        }

        // sostituisco Marketplace con un default se non è stato settato
        $track_id = str_replace("{marketplace}",$this->marketplace["suffix"],$track_id);
        
        // sostituisco Author con un default se non è stato settato
        $track_id = str_replace("{author}",$this->postData->author["name"],$track_id);
        
        // rimuovi label non impostate
        $track_id = $this->removeLabels($track_id);

        // Se non ho trovato nulla metto setto il default
        if (empty($track_id)) $track_id = $this->config["settings"]["trk_default"];
        return $track_id;
        
    }

    public function getGAEvent() {
        // Se è stato settato manualmente prendo quello       
        if ($this->request->getGAEvent()) return $this->request->getGAEvent();

        $ga_event = $this->config["ga_event_template"];

        if (isset($this->config["activation_table"][0])) {
            
            
            // rimuovo amp da template se non sono una pagina amp
            if ($this->postData->is_amp == false) $ga_event = str_replace(["-{amp}","{amp}"],"",$ga_event);
            // aggiungo website
            $ga_event = str_replace("{website}",$this->config["global_settings"]["website_ga"],$ga_event);
            // sostituisco il restante in base alle regole
            foreach(array_reverse($this->config["activation_table"]) as $activation_table)
                if ($this->isValidRule($activation_table) && !empty($activation_table["ga_label"]))
                    $ga_event = str_replace("{".$activation_table["ga_label"]."}",$activation_table["ga_val"],$ga_event);
           
        }
        
        // sostituisco Marketplace con un default se non è stato settato
        $ga_event = str_replace("{marketplace}",$this->marketplace["suffix"],$ga_event);
        
        // sostituisco Author con un default se non è stato settato
        $ga_event = str_replace("{author}",$this->postData->author["name"],$ga_event);
        
        //Sostituisco i placeholder dei link program on gli attributi da shortcode
        if ($this->request->getSubject()) $ga_event = str_replace("{subject}",$this->request->getSubject(),$ga_event);
        if ($this->request->getProgram()) $ga_event = str_replace("{program}",$this->request->getProgram(),$ga_event);
        
        // rimuovi label non impostate
        $ga_event = $this->removeLabels($ga_event);

        // Se non ho trovato nulla metto setto il default
        if (empty($ga_event)) $ga_event = $this->config["settings"]["ga_default"];
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
