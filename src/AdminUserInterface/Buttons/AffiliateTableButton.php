<?php

namespace BLZ_AFFILIATION\AdminUserInterface\Buttons;

/**
 * AffiliateTableButton
 * 
 * Bottone e UI per la creazione degli shortcode che richiama la tabella
 * 
 */
class AffiliateTableButton extends Button {

    function __construct() { 

        /** 
         * Set the name of the plugin 
         * >> affiliate_table_button.js
         * 
         * and the name of the ajax endpoint to call into plugin
         * >> affiliate_table_button_action
         */
        parent::__construct('affiliate_table_button', 'affiliate_table_button_action');
    }


    public function ajax_action() {

        // check for rights
        if ( !Capability::isAuthorEnabled() )  { die( __("Vietato") ); } 


        // get the template 
        $html = file_get_contents( $this->base_dir .'plugins/dialog-AffiliateTableButton.html');
        //include_once(get_template_directory() .'/src/UserInterface/Editor/Buttons/plugins/dialog-AffiliateButton.html' );

        $fields_to_inject =  [ 
            'affiliate_tables' => json_encode( $this->getAffiliateTables() ),
        ];    
        
        
        // inject the variables into the html template
        foreach($fields_to_inject as $key => $value)
            $html = str_replace ( '{{'.$key.'}}' , $value , $html );

        // print the block
        header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
        echo $html;
        die();
    }

    private function getAffiliateTables() { 

        return array_map( function($item) {
            
            return ['id' => $item->ID, 'title' => $item->post_title ];

        }, get_posts( [ 'post_type' => 'affiliate_table', 'post_status' => 'publish', 'posts_per_page' => -1] ));

    }
}