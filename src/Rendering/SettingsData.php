<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Helper;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Config;

/**
 * Ha la responsabilità di ottenere le label degli eventi i tracking id 
 * per la pagina corrente interpretando le regole della tabella
 * 
 * @method string getTemplate()
 * @method string getActivationTableRules()
 * @method string getTrackingID() 
 * @method string getGAEvent()
 * 
 */
class SettingsData { 

    private $postData;
    private $marketpalace;
    private $config;
    private $link_type;
    private $request;

    private $templates = [
        
        'linkButton' => 
        <<<HTML
            <a href="{{ url }}" data-vars-blz-affiliate="{{ ga_event }}" 
               class="affiliation-intext" target="_blank" rel="sponsored"
            >{{ content }}</a>
        HTML,
        'parseLinkAndRender' => 
        <<<HTML
        <a href="{{ url }}" data-vars-blz-affiliate="{{ ga_event }}" 
           class="affiliation-intext" target="_blank" rel="sponsored" >
        HTML,
        'genericButton' => 
        <<<HTML
        <div class="blz_aff_gen_button">
            <a href="{{ url }}" data-vars-blz-affiliate="{{ ga_event }}" 
            class="btn custom_btn" target="_blank" rel="sponsored"
            >{{ content }}</a>
        </div>
        <style>.blz_aff_gen_button { text-align:center }</style>
        HTML,
        
    ];

    public function __construct($link_type,$request) {
        
        $config = Config::loadSettings();
        // COPIO IL TEMPLATE PER GLI ALTRI FORMATI UGUALI
        $this->templates["linkPrograms"] = $this->templates["linkButton"];
        
        $this->postData = PostData::getInstance();
        $this->request = $request;
        
        $this->link_type = Helper::findbySlug($config->pages[0]->controller->settings["tabs"],$link_type);
       
        $this->marketplace = Helper::findbySlug($this->link_type["marketplaces"],$this->request->getMarketplaceKey());
        
        $global_settings = get_option( "blz-affiliation-settings" );
        
        $this->config = [

            "global_settings"      => $global_settings,
            "activation_table"     => get_option($config->pages[0]->slug."-".$this->link_type["slug"]."-".$this->marketplace["slug"]),
            "ga_event_template"    =>  ($this->request->getType() == "button" && isset($this->marketplace["ga_event_template_button"])) ? $this->marketplace["ga_event_template_button"] : $this->marketplace["ga_event_template"],
            "tracking_id_template" =>  ($this->request->getType() == "button" && isset($this->marketplace["tracking_id_button"])) ? $this->marketplace["tracking_id_button"] : $this->marketplace["tracking_id"]
        ];
    }


    public function getTemplate() {
        $linkType = ($this->request->getType() == "button") ? "genericButton" : $this->link_type["slug"];
        return $this->templates[ $linkType ];
    }

    /**
     * Undocumented function
     *
     * @param string $code - un template con placeholder tra parentesi graffe {}
     * @param string $type - 'GA' | 'TRK_ID'
     * @return void
     */
    private function getActivationTableRules( string $code, string $type ){
        
        /// verifica che esista una riga?
        if ( ! isset ( $this->config["activation_table"][0] ) ) 
            return $code;
        // rimuovo amp da template se non sono una pagina amp
        if ( $this->postData->is_amp == "false" ) $code = str_replace("{amp}","",$code);
        
        // aggiungo website 
        $code = str_replace( "{website}", $this->config["global_settings"]["website_".$type], $code);

        // sostituisco il restante in base alle regole   
        foreach( array_reverse( $this->config[ "activation_table" ] ) as $rule ) {
            
            // CASO IN CUI PRENDO IL VALORE DAL TIPO ATTIVATORE
            if( $rule["regola"] == "this_value" )
                $code = str_replace( "{".$rule["ga_label"]."}", $this->getValue($rule), $code );

            // GLI ALTRI CASI SE L'ATTIVATORE È VALIDO
            if( !empty( $rule ) )
                if ( ( $this->isValidRule($rule) && !empty( $rule[ "ga_label" ] )) || $rule["attivatore"] == "tutte" )
                    $code = str_replace("{".$rule["ga_label"]."}",$rule[$type."_val"],$code);
        }
        
        return $code;
    }

    public function getTrackingID() {
        // Se è stato settato manualmente prendo quello
        $track_id = ($this->request->getTrackingId()) ? $this->request->getTrackingId()."{amp}" : $this->config["tracking_id_template"];
        
        // carica regole dalla tabella attivazione
        $track_id = $this->getActivationTableRules($track_id,"trk");
        
        // rimuovi placeholder non impostati
        $track_id = $this->cleanCode($track_id);

        return $track_id;
        
    }

    public function getGAEvent() {
        // Se è stato settato manualmente prendo quello       
        $ga_event = ($this->request->getGAEvent()) ? $this->request->getGAEvent()." {amp}" : $this->config["ga_event_template"];
        
        // carica regole dalla tabella attivazione
        $ga_event = $this->getActivationTableRules($ga_event,"ga");
        
        //Sostituisco i placeholder dei link program on gli attributi da shortcode
        if ($this->link_type["slug"] == "linkPrograms")  {
            $ga_event = str_replace("{subject}",$this->request->getSubject(),$ga_event);
            $ga_event = str_replace("{program}",$this->request->getProgram(),$ga_event);
        } 
        if ($this->link_type["slug"] == "blz_table") {
            $ga_event = str_replace("{table-name}",$this->request->getKeyword(),$ga_event);
            $ga_event = str_replace("{numero-posizione}","Posizione " .$this->request->getPosition(),$ga_event);
            $ga_event = str_replace("{marketplace}",$this->request->getMarketplace(),$ga_event);
        }
        // rimuovi placeholder non impostati
        $ga_event = $this->cleanCode($ga_event);

        return $ga_event;
    }

   
    /**
     * Ritorna il valore della colonna "attivatore" di una delle righe della tabella
     *
     * [ refactoring ] Meccanismo che forse potrebbe andare 
     *                 in un oggetto che implementa una qualche interfaccia "Rule"
     * 
     * @param [type] $activation_table
     * @return void
     */
    private function getValue( $rule ) {
       
        switch ( $rule["attivatore"] ) {
            
            case "POSTTYPE":
                return $this->postData->post_type;
            
            case "USERS":
                return $this->postData->author["name"];
            
            default:                
                return $this->postData->taxonomies[ $rule[ "attivatore" ] ][0];            
        } 
    }

    /**
     * Ritorna true se una regola ( una delle righe della tabella ) è verificata
     *
     * [ refactoring ]--> l'oggetto Rule dovrebbe avere il meccanismo per verificare la pagina 
     * 
     * @param array $rule - riga della tabella  
     * @return boolean
     */
    private function isValidRule( array $rule ) {

        //print_r($activation_table["attivatore"]);
        
        /// in base al tipo di attivatore sulla riga
        switch ($rule["attivatore"]) {

            /// verifica posttype, utente o tassonomia
            case "POSTTYPE":                
                return $rule["regola"] == $this->postData->post_type || $rule["regola"] == "custom_value";
                
            case "USERS":
                return $rule["regola"] == $this->postData->author["id"] || $rule["regola"] == "custom_value";

            default:                
                
                if( empty( $this->postData->taxonomies[ $rule["attivatore"]] )) {
                    
                    return false;

                } else {

                    return in_array( $rule["regola"], $this->postData->taxonomies[$rule["attivatore"]]) || $rule["regola"] == "custom_value";
                }

        } 
    }

    /**
     * Ripulisce il template da caratteri non desiderati
     *
     * @param string $template
     * @return void
     */
    private function cleanCode( $template ) {

        // rimuovo placeholder non rimpiazzati
        $template = preg_replace( '/{\s*(.*?)\s*}/', '', $template );

        // rimuovo anche eventuali doppi spazi        
        $template = trim( preg_replace( '/\s+/', ' ', $template ) );
        
        return $template;
    }


}
