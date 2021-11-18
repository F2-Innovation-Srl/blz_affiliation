<?php

namespace BLZ_AFFILIATION\AdminUserInterface\Buttons;

/*
 *  Class AffiliateLinkButton
 *  
 *  Bottone e UI per la creazione degli shortcode dei link
 */
class AffiliateLinkProgramsButton extends Button {

    function __construct() { 
        
        /** 
         * Set the name of the plugin 
         * >> affiliate_link_programs_button.js
         * 
         * and the name of the ajax endpoint to call into plugin
         * >> affiliate_link_programs_action
         */
        parent::__construct('affiliate_link_programs_button', 'affiliate_link_programs_action');
        
    }


    /**
     * This is the function called from the plugin
     * as endpoint      
     */
    public function ajax_action() {

        // check for rights
        if (! current_user_can('publish_posts'))  { die( __("Vietato") ); } 
        
        // get the template 
        $html = file_get_contents( $this->base_dir .'plugins/dialog-AffiliateLinkProgramsButton.html');
        //include_once(get_template_directory() .'/src/UserInterface/Editor/Buttons/plugins/dialog-AffiliateButton.html' );


        $fields_to_inject =  [ 
            'author_tracking_ids' => get_field( 'amazon_tracking_id', 'user_'.get_current_user_id() ) ,
            'subjects' => json_encode([ 'vpn', 'antivirus', 'hosting' ]),
            'programs' => json_encode([ 'cj', 'awin', 'ciaone'])
        ];    
        
        
        // inject the variables into the html template
        foreach($fields_to_inject as $key => $value)
            $html = str_replace ( '{{'.$key.'}}' , $value , $html );
        

        // print the block
        header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
        echo $html;        
        die();
    }
}