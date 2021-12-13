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
        'parseLinkAndRender' => <<<HTML

        <a href="{{ url }}" data-vars-affiliate="{{ ga_event }}" 
           class="affiliation-intext" target="_blank" rel="sponsored" >
        HTML
    ];

    public function __construct($postData,$link_type,$request) {
        
        // COPIO IL TEMPLATE PER GLI ALTRI FORNATI UGUALI
        $this->templates["linkPrograms"] = $this->templates["linkButton"];
        
        $this->postData = $postData;
        $this->request = $request;
        $this->link_type = Config::findbySuffix(CONFIG["Items"][0]["settings"]["tabs"],$link_type);
  
        $this->marketplace = Config::findbySuffix($this->link_type["marketplaces"],$this->request->getMarketplaceKey());
        $global_settings = get_option( "blz-affiliation-settings" );
        $this->config = [
            "global_settings" => $global_settings,
            "activation_table" => get_option(CONFIG["Items"][0]["suffix"]."-".$this->link_type["suffix"]."-".$this->marketplace["suffix"]),
            "ga_event_template" =>  $this->marketplace["ga_event_template"],
            "tracking_id_template" =>  $this->marketplace["tracking_id"],
            
        ];

    }


    public function getTemplate() {
    
        return $this->templates[$this->link_type["suffix"]];
    }

    private function getActivationTableRules($code,$type){
        if (isset($this->config["activation_table"][0])) {
            
            // rimuovo amp da template se non sono una pagina amp
            if ($this->postData->is_amp == false) $code = str_replace("{amp}","",$code);
            // aggiungo website
            $code = str_replace("{website}",$this->config["global_settings"]["website_".$type],$code);
            // sostituisco il restante in base alle regole
            foreach(array_reverse($this->config["activation_table"]) as $activation_table)
                if ($this->isValidRule($activation_table) && !empty($activation_table[$type."_label"]))
                $code = str_replace("{".$activation_table[$type."_label"]."}",$activation_table[$type."_val"],$code);
        }
        return $code;
    }

    public function getTrackingID() {
        // Se è stato settato manualmente prendo quello
        if ($this->request->getTrackingId()) return $this->request->getTrackingId();
        
        // carica regole dalla tabella attivazione
        $track_id = $this->getActivationTableRules($this->config["tracking_id_template"],"trk");

        // sostituisco Marketplace e Author con un default se non è stato settato
        $track_id = str_replace("{marketplace}",$this->marketplace["suffix"],$track_id);
        $track_id = str_replace("{author}",$this->postData->author["name"],$track_id);
        
        // rimuovi placeholder non impostati
        $track_id = $this->cleanCode($track_id);

        return $track_id;
        
    }

    public function getGAEvent() {
        // Se è stato settato manualmente prendo quello       
        if ($this->request->getGAEvent()) return $this->request->getGAEvent();

        // carica regole dalla tabella attivazione
        $ga_event = $this->getActivationTableRules($this->config["ga_event_template"],"ga");

        // sostituisco Marketplace e Author con un default se non è stato settato
        $ga_event = str_replace("{marketplace}",$this->marketplace["suffix"],$ga_event);
        $ga_event = str_replace("{author}",$this->postData->author["name"],$ga_event);
        
        //Sostituisco i placeholder dei link program on gli attributi da shortcode
        if ($this->request->getSubject()) $ga_event = str_replace("{subject}",$this->request->getSubject(),$ga_event);
        if ($this->request->getProgram()) $ga_event = str_replace("{program}",$this->request->getProgram(),$ga_event);
        
        // rimuovi placeholder non impostati
        $ga_event = $this->cleanCode($ga_event);

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

    private function cleanCode($str) {

        $regex = '/{\s*(.*?)\s*}/';
        $code = preg_replace( $regex, "", $str);

        return $code;

    }


}
