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

use BLZ\Core;
use BLZ\Hooks;
use BLZ\AdminUserInterface;

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
		
		Hooks\AffiliateContentHooks::init();
		Core\Shortcodes\AffiliateButtonsShortcode::init();
		$this->btn = new AdminUserInterface\Buttons\EditorTrackedButton();
	}
}

$blzAffiliate = new BlzAffiliate();
