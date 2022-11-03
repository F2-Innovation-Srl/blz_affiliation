<?php
/**
 * Plugin Name: Blazemdia Affiliation
 * Plugin URI: https://www.blazemedia.it/
 * Description: This is a Blazemedia plugin for links affiliation management.
 * Version: 1.8.82
 * Author: Blazemedia
 * Author URI: https://halfelf.org/
 * License: http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain: blz-affiliation
 * Network: false
 *
 * @package blz-affiliation
 *
 * Copyright 2022 Blazemedia (email: techteam@blazemedia.it)
 *
 */
define( 'BLZ_AFFILIATION_PATH' , plugin_dir_path( __FILE__ ) );
define( 'BLZ_AFFILIATION_URI'  , plugin_dir_url( __FILE__ ));
define( 'BLZ_AFFILIATION_VERSION'  , "1.8.82");


require_once BLZ_AFFILIATION_PATH . '/vendor/autoload.php';

use BLZ_AFFILIATION\PostTypes;
use BLZ_AFFILIATION\Taxonomies;
use BLZ_AFFILIATION\AdminUserInterface\Settings;
use BLZ_AFFILIATION\AdminUserInterface\Buttons;
use BLZ_AFFILIATION\Rendering;
use BLZ_AFFILIATION\Rendering\ParseLinkAndRender\ParseLinkAndRender;


class BlzAffiliate {
			
	public function __construct() {

		/// Imposta ruolo Affiliation Manager e assegna le capability
		/// per quel ruolo e per gli amministratori
		$blz_aff_cap = Settings\Capability::getInstance();
		$blz_aff_pt  = Settings\PostTypes::getInstance();

		/// crea i custom post type (tabelle e link "centralizzati")
		PostTypes\AffiliateTables::init();
		PostTypes\AffiliateLinkProgamStored::init();

		// crea le tassonomie per i program link
		Taxonomies\AffiliateLinkProgram::init();

		if( is_admin() ) {

			/// mostra le impostazioni in amministrazione
			$this->admin();

		} else {

			/// effettua il rendering dei formati in pagina
			add_action('wp', [ $this, 'render' ] );

		}
	}

	function render() {

		/// verifica che il post type corrente sia abilitato
		if ( !Settings\PostTypes::isPostTypeEnabled() ) return;

		/// effettua il rendering degli shortcode dei bottoni 
		/// di affiliazione
		new Rendering\AffiliateLinkButton();
		new Rendering\AffiliateGenericButton();
		new Rendering\AffiliateCustomLinkButton();
		new Rendering\AffiliateLinkProgramsButton();
		new Rendering\AffiliateLinkProgramStoredButton();
		
		/// abilita il parsing e il rendering dei link
		/// di affiliazione nel testo
		new ParseLinkAndRender();

		/// effettua il rendering degli shortcode delle 
		/// tabelle di affiliazione
		new Rendering\AffiliateTable();

		/// aggiunge le dipendenze js per il tracciamento
		new Rendering\Settings\GATracking();

		/// aggiunge css con parametri presi dai settings
		new Rendering\Settings\StyleInjector();

		/// aggiunge js con parametri presi dai settings
		new Rendering\Settings\ScriptInjector();

		/// aggiunge il Disclaimer
		new Rendering\Disclaimer();

	}

	private function admin(){

		/// aggiunge la pagina dei settings del plugin backend
		new Settings\AdminPage();

		$config = Settings\Config::loadSettings();

        if( $config->is_valid ) {
			
			/// aggiunge i bottoni per i link di affiliazione		
			new Buttons\AffiliateLinkButton();
			new Buttons\AffiliateGenericButton();
			new Buttons\AffiliateLinkProgramsButton();		
			new Buttons\AffiliateLinkProgramStoredButton();
			
			/// aggiunge il bottone per selezionare le tabelle di affiliazione
			/// a aggiungere il relativo shortcode in pagina
			new Buttons\AffiliateTableButton();

			new Settings\AdminScriptInjector;

		}
	}
}


$blzAffiliate = new BlzAffiliate();