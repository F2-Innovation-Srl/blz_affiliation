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

    public function __construct($postData,$link_type,$marketpalace) {
        $this->postData = $postData;
        $this->link_type = $link_type;
        $this->marketplace = Config::findbySuffix(CONFIG["Items"][0]["settings"]["marketplaces"],$marketpalace);

        $this->config = [
            "settings" => get_option(CONFIG["Items"][0]["suffix"]."-".$marketpalace."-".$link_type."_settings"),
            
            "activation_table" => get_option( CONFIG["Items"][0]["suffix"]."-".$marketpalace."-".$link_type),

            "ga_event_template" =>  $this->marketplace["ga_event_template"],
            "tracking_id_template" =>  $this->marketplace["tracking_id"],
            
        ];
    }


    public function getTemplate() {
    
        return $this->templates[$this->link_type];
    }



    public function getTrackingID() {

        $track_id = $this->config["settings"]["trk_default"];
    
        if (isset($this->config["activation_table"][0])) {

            $track_id = $this->config["tracking_id_template"];

            // rimuove amp da template se non è una pagina amp
            if ($this->postData->is_amp == false) $track_id = str_replace(["-{amp}","{amp}"],"",$track_id);
            
            // aggiunge website
            $track_id = str_replace("{website}",$this->config["settings"]["website_trk"],$track_id);

            // sostituisce il restante in base alle regole
            foreach( $this->config[ "activation_table" ] as $activation_table ){

                ///  verifica che esista una regola valida
                if ( $this->isValidRule( $activation_table ) && !empty( $activation_table["ga_label"] )) {

                    /// si possono customizzare anche le label dei tracking id                    
                    $track_id = str_replace("{".$activation_table["trk_label"]."}", $activation_table["trk_val"], $track_id );
                }
            }
                    
            // sostituisco Marketplace con un default se non è stato settato
            $track_id = str_replace("{marketplace}",$this->marketplace["suffix"],$track_id);

            // sostituisco Author con un default se non è stato settato
            $track_id = str_replace("{author}",$this->postData->author["name"],$track_id);

            // rimuovi label non impostate
            $track_id = $this->removeLabels( $track_id );
        }

        return $track_id;
    }


    public function getGAEvent() {
        
        /// condizione di ritorno del valore di default
        if ( !isset( $this->config["activation_table"][0] )) {

            return $this->config["settings"]["ga_default"];
        }
        
        /// prende il template
        $ga_event = $this->config["ga_event_template"];

        /* [REVIEW] amp forse dovrebbe essere semplicemente una regola come altre ??*/

        // rimuovo amp da template se non sono una pagina amp
        if ($this->postData->is_amp == false) $ga_event = str_replace(["-{amp}","{amp}"],"",$ga_event);
        
        
        /// sostituisce il valore di { website }
        $ga_event = preg_replace( '/\{\s*website\s*\}/', $this->config["settings"]["website_ga"], $ga_event );

        // sostituisco il restante in base alle regole
        foreach( $this->config[ "activation_table" ] as $rule ) {



            if( $rule->appliesTo( $this->postData, 'ga_label' ) ) {

                $regex = '/\{\s*' . $rule["ga_label"] . '\s*\}/';

                $ga_event = preg_replace( $regex, $rule["ga_val"], $ga_event );

            }

            /// se la regola è verificata ed esiste esista una ga_label
            if ( $this->isRuleVerified( $rule ) && !empty( $rule[ "ga_label" ] )) {

                $regex = '/\{\s*' . $rule["ga_label"] . '\s*\}/';

                $ga_event = preg_replace( $regex, $rule["ga_val"], $ga_event );
            }
        }

        /// a questo punto sono stati sostituiti tutti i valori
        /// si procede a sostituire i valori di default per quei
        /// placeholder che non sono stati valorizzati

        /// default per { marketplace } e { author }
        return preg_replace( 
            [ '/{\s*marketplace\s*}/', '/{\s*author\s*}/'],
            [ $this->marketplace[ "suffix" ], $this->postData->author["name"] ],
            $ga_event
        );

    }


    
    private function isValidRule( $activation_table ) {
        
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
