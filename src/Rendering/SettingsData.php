<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\Utils\Config;

/**
 * Quali sono le responsabilità di questa classe?
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
    
        return $this->templates[ $this->link_type["suffix"] ];
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
        if ( !$this->postData->is_amp ) $code = str_replace("{amp}","",$code);

        // aggiungo website 
        $code = str_replace( "{website}", $this->config["global_settings"]["website_".$type], $code);

        // sostituisco il restante in base alle regole   
        foreach( array_reverse( $this->config[ "activation_table" ] ) as $activation_table ) {
            
            // CASO IN CUI PRENDO IL VALORE DAL TIPO ATTIVATORE
            if ($activation_table["regola"] == "this_value")
                $code = str_replace("{".$activation_table[$type."_label"]."}",$this->getValue($activation_table),$code);

            // GLI ALTRI CASI SE L'ATTIVATORE È VALIDO
            if (($this->isValidRule($activation_table) && !empty($activation_table[$type."_label"])) || $activation_table["attivatore"] == "tutte")
                $code = str_replace("{".$activation_table[$type."_label"]."}",$activation_table[$type."_val"],$code);
        }
       
        return $code;
    }

    public function getTrackingID() {
        // Se è stato settato manualmente prendo quello
        if ($this->request->getTrackingId()) return $this->request->getTrackingId();
        
        // carica regole dalla tabella attivazione
        $track_id = $this->getActivationTableRules($this->config["tracking_id_template"],"trk");
        
        // rimuovi placeholder non impostati
        $track_id = $this->cleanCode($track_id);

        return $track_id;
        
    }

    public function getGAEvent() {
        // Se è stato settato manualmente prendo quello       
        if ($this->request->getGAEvent()) return $this->request->getGAEvent();

        // carica regole dalla tabella attivazione
        $ga_event = $this->getActivationTableRules($this->config["ga_event_template"],"ga");

        //Sostituisco i placeholder dei link program on gli attributi da shortcode
        if ($this->link_type == "linkPrograms") $ga_event = str_replace("{subject}",$this->request->getSubject(),$ga_event);
        if ($this->link_type == "linkPrograms") $ga_event = str_replace("{program}",$this->request->getProgram(),$ga_event);
        if ($this->link_type == "blz_table") $ga_event = str_replace("{numero-posizione}","Positione " .$this->request->getPosition(),$ga_event);
        if ($this->link_type == "blz_table") $ga_event = str_replace("{marketplace}",$this->request->getMarketplace(),$ga_event);
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
                return in_array( $rule["regola"], $this->postData->taxonomies[$rule["attivatore"]]) || $rule["regola"] == "custom_value";
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
