<?php

namespace BLZ_AFFILIATION\AdminUserInterface\Buttons;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Capability;

/*
 *  Class AffiliateLinkButton
 *  
 *  Bottone e UI per la creazione degli shortcode dei link
 */
class AffiliateGenericButton extends Button {

    function __construct() { 
        
        /** 
         * Set the name of the plugin 
         * >> editor_generic_button.js
         * 
         * and the name of the ajax endpoint to call into plugin
         * >> editor_generic_action
         */
        parent::__construct('editor_generic_button', 'editor_generic_action');
        
    }


    /**
     * This is the function called from the plugin
     * as endpoint      
     */
    public function ajax_action() {

        // check for rights
        if ( !Capability::isAuthorEnabled() )  { die( __("Vietato") ); } 
        
        // get the template 
        $html = file_get_contents( $this->base_dir .'plugins/dialog-AffiliateGenericButton.html');
        
        //include_once(get_template_directory() .'/src/UserInterface/Editor/Buttons/plugins/dialog-AffiliateGenericButton.html' );

        // print the block
        header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
        echo $html;        
        die();
    }
}