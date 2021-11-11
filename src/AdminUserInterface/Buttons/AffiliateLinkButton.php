<?php

namespace BLZ_AFFILIATION\AdminUserInterface\Buttons;

/*
 *  Class AffiliateLinkButton
 *  
 */
class AffiliateLinkButton extends Button {

    function __construct() { 
        
        /** 
         * Set the name of the plugin 
         * >> editor_tracked_button.js
         * 
         * and the name of the ajax endpoint to call into plugin
         * >> editor_tracked_action
         */
        parent::__construct('editor_tracked_button', 'editor_tracked_action');
        
    }


    /**
     * This is the function called from the plugin
     * as endpoint      
     */
    public function ajax_action() {

        // check for rights
        if (! current_user_can('publish_posts'))  { die( __("Vietato") ); } 
        
        // get the template 
        $html = file_get_contents( $this->base_dir .'plugins/dialog-AffiliateLinkButton.html');
        //include_once(get_template_directory() .'/src/UserInterface/Editor/Buttons/plugins/dialog-AffiliateButton.html' );

        $tracking_ids =  [
            'amazon'      => get_field('amazon_tracking_id', 'user_'.get_current_user_id()),
            'ebay'        => get_field('ebay_tracking_id', 'user_'.get_current_user_id()),
            'ebay_used'   => get_field('ebay_tracking_id', 'user_'.get_current_user_id()),
            'trovaprezzi' => get_field('trovaprezzi_tracking_id', 'user_'.get_current_user_id())
        ];    
        
        // inject the variables into the html template
        foreach($tracking_ids as $mkt => $tracking_id)
            $html = str_replace ( '{{'.$mkt.'-tracking-id}}' , $tracking_id , $html );

        // print the block
        header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
        echo $html;        
        die();
    }
}