<?php

namespace BLZ_AFFILIATION\AdminUserInterface\Buttons;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Capability;

/*
 *  Class AffiliateButton ( nuovo )
 *  
 *  Bottone e UI per la creazione degli shortcode dei link
 */
class AffiliateButton extends Button {

    function __construct() { 
        
        /** 
         * Set the name of the plugin 
         * >> affiliation_button.js
         * 
         * and the name of the ajax endpoint to call into plugin
         * >> affiliation_button_action
         */
        parent::__construct('affiliation_button', 'affiliation_button_action');
        
    }


    /**
     * This is the function called from the plugin
     * as endpoint
     */
    public function ajax_action() {

        // check for rights
        if ( !Capability::isAuthorEnabled() ) { die( __("Vietato") ); } 
        
        // get the template 
        $html = file_get_contents( $this->base_dir .'plugins/dialog-AffiliateButton.html');
        
        //include_once(get_template_directory() .'/src/UserInterface/Editor/Buttons/plugins/dialog-AffiliateButton.html' );

        $fields_to_inject = file_get_contents (
            'https://links-master-book.blazemedia.it/wp-json/wp/v2/all-data/', false, stream_context_create([
                
                "ssl" => [
                    "verify_peer"=>false, "verify_peer_name"=>false,
                ]
            ])
        );

        var_dump( $fields_to_inject ); die;
        
        // inject the variables into the html template
        foreach( $fields_to_inject as $key => $value ) {

            $html = str_replace ( '{{'.$key.'}}' , $value , $html );
        }

        // print the block
        header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
        echo $html;        
        die();
    }
}