<?php
/**
 * Plugin Name: Blazemdia Affiliation
 * Plugin URI: https://www.blazemedia.it/
 * Description: This is a Blazemedia plugin for links affiliation management.
 * Version: 1.0
 * Author: Blazemedia
 * Author URI: https://halfelf.org/
 * License: http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain: blz-affiliate
 * Network: true
 *
 * @package blz-affiliate
 *
 * Copyright 2021 Blazemedia (email: techteam@f2innovation.com)
 *
 */
define( 'PLUGIN_PATH' , plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_URI'  , plugin_dir_url( __FILE__ ));

require_once PLUGIN_PATH . '/vendor/autoload.php';

use BLZ_AFFILIATION\Rendering;
use BLZ_AFFILIATION\AdminUserInterface;
use BLZ_AFFILIATION\AdminUserInterface\Settings;
use BLZ_AFFILIATION\CustomPostTypes;
use BLZ_AFFILIATION\AffiliateMarketing\Marketplace;
use BLZ_AFFILIATION\AffiliateMarketing\Request;
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
		//CustomPostTypes\AffiliateTables::init();
		//CustomPostTypes\AffiliateLinkProgamStored::init();
		
		/// effettua il rendering degli shortcode dei bottoni 
		/// di affiliazione
		new Rendering\AffiliateLinkButton();
		new Rendering\AffiliateCustomLinkButton();
		new Rendering\AffiliateLinkProgramsButton();
		new Rendering\AffiliateLinkProgramStoredButton();

		/// abilita il parsing e il rendering dei link
		/// di affiliazione nel testo
		//Rendering\ParseLinkAndRender::init();

		/// effettua il rendering degli shortcode delle 
		/// tabelle di affiliazione		
		//new Rendering\AffiliateTable();
		

		/// aggiunge i bottoni per i link di affiliazione
		new AdminUserInterface\Buttons\AffiliateLinkButton();
		new AdminUserInterface\Buttons\AffiliateLinkProgramsButton();		
		new AdminUserInterface\Buttons\AffiliateLinkProgramStoredButton();
		
		/// aggiunge il bottone per selezionare le tabelle di affiliazione
		/// a aggiungere il relativo shortcode in pagina
		//new AdminUserInterface\Buttons\AffiliateTableButton();

		/// aggiunge la pagina dei settings del plugin
		# load config
		define('CONFIG', json_decode(file_get_contents(PLUGIN_PATH.'config.json'), true));
		new AdminUserInterface\Settings\AdminPage();
		
	}
}

$blzAffiliate = new BlzAffiliate();
