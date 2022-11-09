<?php

namespace BLZ_AFFILIATION\Utils;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Config;

/**
 * [todo] Meglio non avere una classe Helper, che è un potenziale buco nero.
 */

class Helper {

    /**
     * Prende in ingresso il link 
     * 
     * e deve pulire il link dai parametri o lo restituisce come è
     * 
     * [ todo ] O facciamo un trait, o creiamo una classe apposit aper la gestione dei parametri 
     *          oppure diventa un elemento di una classe base ereditato da altre classi che lo usano
     *
     * @param string $link
     * @param string $marketplace
     * @return string
     */
    static function cleanParams( $link, $marketplace = "" ) {
        
        /// verifica se il campo che "forza la sostituzione del tracciamento"
        /// è flaggato o no
        $enabled_override = self::isTrackingEnabled( $marketplace );

        /// se è un parse&render capisce il marketplace dalla url
        if( empty( $marketplace ) ){

            $marketplace = strpos( $link, "ebay" ) !== false ? "ebay" : ( strpos($link, "amazon") !== false ? "amazon" : "" );
        }
        
        if ( $enabled_override || ( !$enabled_override && !( strpos( $link, 'tag=' ) !== false || strpos( $link, 'campid=' ) !== false )) ) {
           
            $link = self::replaceTag( $link, $marketplace );
        }

        return $link;

    }


    /**
     * [ todo ] Meglio definire una classe apposita per le fuzionalità 
     *          in questo caso potrebbe essere LinkTagOverride
     *
     * @param [type] $link
     * @param [type] $marketplace
     * @return void
     */
    static function replaceTag($link, $marketplace){

        if (strpos($marketplace, "amazon") !== false) {
            $link = ( strpos( $link, 'tag=' ) === false ) ? $link : preg_filter('/(.*)\?.*/', '$1', $link );
            $link = ( strpos( $link, '?' ) === false ) ? $link .'?tag={tracking_id}' :  $link .'&tag={tracking_id}';
            return $link;
        }
        
        if (strpos($marketplace, "ebay") !== false) {

            $settings = get_option( "blz-affiliation-settings" );
            $ebay_campain_id = ( isset( $settings['ebay_campain_id'] )) ? $settings['ebay_campain_id'] : "5338741871";
    
            $link = strpos( $link, '?' ) === false ? $link : preg_filter('/(.*)\?.*/', '$1', $link );

            $params = implode( '&', [
                'mkevt=1',
                'toolid=10001',
                'mkcid=1',
                'mkrid=724-53478-19255-0',
                'siteid=101',
                'campid='.$ebay_campain_id,
                'customid={tracking_id}'
            ]);
            
            $prefix = strpos( $link, '?' ) === false ? '?' : '&';
            return $link . $prefix . $params;

        }
    }


    /**
     * Replace special characters and spaces in a given string
     * and return the result lowering capital letters
     * 
     * [ todo ] Esiste una funzione di wordpress che si chiama 
     *          sanitize_title() dovremmo utilizzare quella
     *          altrimenti possiamo creare una classe TextHelper, StringHelper o solo Text
     *          che espone metodi che modificano testi o stringhe
     *          esempio
     *          Text::sanitize( $text ) 
     *          oppure
     *          Text::slugify( $text ) 
     * 
     */
    static function slugify($text) {

        $text = str_replace('à', 'a', $text);
        $text = str_replace(array('è','é'), 'e', $text);
        $text = str_replace('ì', 'i', $text);
        $text = str_replace('ò', 'o', $text);
        $text = str_replace('ù', 'u', $text);
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }


    public static function pre($obj,$die)
    {
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
        if ($die) exit;

    }

    /**
     * Cerca nel config tramite chiave
     * 
     * [ todo ] Qui si tratta di creare un gestore delle options, da riprogettare
     */
    public static function findbySlug($obj,$val){
        return $obj[array_search($val, array_column($obj, 'slug'))];
    } 

    /**
     * Torna l'api name dal settings
     * 
     * [ todo ] Qui si tratta di creare un gestore delle options, da riprogettare
     */
    public static function getApiSlug($marketplace){
        $config = Config::loadSettings();
        return  Helper::findbySlug($config->pages[0]->controller->settings["tabs"][0]["marketplaces"],$marketplace)["api_slug"];
    }

    /**
     * Ritorna true se lo stato del tracking è disabilitato, false altrimenti
     * 
     * [ todo ] Ma è duplicata?
     * 
     * @return bool
     */
    public static function isTrackingEnabled($marketplace) {
        
        $settings = get_option( "blz-affiliation-settings-js" );
        
        //disabilita l'override del tracking_id da tabella attivazione solo se è un parse&render ed è flaggata la disabilitazione
        if (empty($marketplace)) return true;

        return isset( $settings[ 'tracking_enable' ] ) ? ( ($settings[ 'tracking_enable' ] == "true") ? true : false ) : false;

    }

    /**
     * Ritorna true se il Tracker è disabilitato, false altrimenti
     * 
     * [ todo ] Creare un punto di accesso più esplicito alle opzioni
     *          in questo caso sarebbe meglio avere un TrackerOption
     *
     * @return bool
     */
    public static function isTrackerEnabled() {

        $settings = get_option( "blz-affiliation-settings-js" );

        return isset( $settings['tracker_enable'] ) ? ( ($settings[ 'tracker_enable' ] == "true") ? true : false ) : false;
    }


    /**
     * Imposta il valore is_affiliation_page a true in config
     * se la pagina ha un link affiliato
     *      
     */
    public static function setAffiliationPage() {

        $config = Config::loadSettings();

        $config->is_affiliation_page = "true"; 
    }


    /**
     * Torna la lista dei pattern da verificare
     * 
     * [ todo ] se serve bisogna metterla in un file di configurazione e richiamarla come proprietà statica
     *          dove la utilizziamo?         
     * 
     * @return array
     */
    public static  function getMarketplacePatterns() {

        return [
            'Amazon',
            'Ebay',
            'AmazonShorted', 
            'AmazonPrimeVideo',
            'EbayShorted', 
            'PrettyLink', 
        ];
    }
}
