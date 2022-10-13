<?php
/**
 * Plugin Name: Blazemdia Affiliation
 * Plugin URI: https://www.blazemedia.it/
 * Description: This is a Blazemedia plugin for links affiliation management.
 * Version: 1.8.1
 * Author: Blazemedia
 * Author URI: https://halfelf.org/
 * License: http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain: blz-affiliation
 * Network: true
 *
 * @package blz-affiliation
 *
 * Copyright 2021 Blazemedia (email: techteam@blazemedia.it)
 *
 */
define( 'PLUGIN_PATH' , plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_URI'  , plugin_dir_url( __FILE__ ));
define( 'PLUGIN_VERSION'  , "1.8.4");

require_once PLUGIN_PATH . '/vendor/autoload.php';

use BLZ_AFFILIATION\Utils\Config;
use BLZ_AFFILIATION\PostTypes;
use BLZ_AFFILIATION\Taxonomies;
use BLZ_AFFILIATION\AdminUserInterface\Settings;
use BLZ_AFFILIATION\AdminUserInterface\Buttons;
use BLZ_AFFILIATION\Rendering;
use BLZ_AFFILIATION\Rendering\ParseLinkAndRender\ParseLinkAndRender;
/**
 *
 */
class BlzAffiliate {
	
	private $btn;
	/**
	 * Init
	 *
	 * @since 1.0
	 * @access public
	 */
	
	public function __construct() {

		/// crea i custom post type (tabelle e link "centralizzati")
		PostTypes\AffiliateTables::init();
		PostTypes\AffiliateLinkProgamStored::init();

		// crea le tassonomie per i program link
		Taxonomies\AffiliateLinkProgram::init();

		if (!is_admin()){
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
		}
		/// aggiunge i bottoni per i link di affiliazione		
		new Buttons\AffiliateLinkButton();
		new Buttons\AffiliateGenericButton();
		new Buttons\AffiliateLinkProgramsButton();		
		new Buttons\AffiliateLinkProgramStoredButton();
		
		/// aggiunge il bottone per selezionare le tabelle di affiliazione
		/// a aggiungere il relativo shortcode in pagina
		new Buttons\AffiliateTableButton();
	
	
		/// aggiunge la pagina dei settings del plugin backend
		new Settings\AdminPage();

		/// aggiunge le dipendenze js per il tracciamento
		new Rendering\Settings\GATracking();

		/// aggiunge css con parametri presi dai settings
		new Rendering\Settings\StyleInjector();

		/// aggiunge il Disclaimer
		new Rendering\Disclaimer();
		
	}
}

$blzAffiliate = new BlzAffiliate();
