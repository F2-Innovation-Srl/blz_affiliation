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
use BLZ_AFFILIATION\CustomPostTypes;

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
		
		/// crea il custom post type per le tabelle 
		/// di affiliazione
		CustomPostTypes\AffiliateTables::init();

		/// effettua il rendering degli shortcode dei bottoni 
		/// di affiliazione
		Rendering\AffiliateLinkButton::init();

		/// abilita il parsing e il rendering dei link
		/// di affiliazione nel testo
		Rendering\ParseLinkAndRender::init();

		/// effettua il rendering degli shortcode delle 
		/// tabelle di affiliazione		
		new Rendering\AffiliateTable();
		
		
		/// aggiunge il bottone per i link di affiliazione nell'editor
		new AdminUserInterface\Buttons\AffiliateLinkButton();

		/// aggiunge il bottone per selezionare le tabelle di affiliazione
		/// a aggiungere il relativo shortcode in pagina
		new AdminUserInterface\Buttons\AffiliateTableButton();

		/// aggiunge la pagina dei settings del plugin
		new AdminUserInterface\PluginSettings();
		
	}
}

$blzAffiliate = new BlzAffiliate();
