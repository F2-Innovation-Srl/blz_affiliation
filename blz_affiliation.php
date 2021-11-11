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

/**
 * Purge Class
 *
 * @since 1.0
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
		
		ParseLinkAndRender::init();
		AffiliateLinkButton::init();
		new AdminUserInterface\Buttons\AffiliateLinkButton();
		new AdminUserInterface\Settings();
	}
}

$blzAffiliate = new BlzAffiliate();
