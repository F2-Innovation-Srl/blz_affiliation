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
        
         /// prende il valore del post
         $post_id = intval($_GET['post'] );
         
         // get the template 
         $html = file_get_contents( $this->base_dir .'plugins/dialog-AffiliateLinkProgramsButton.html');
 
         // GET term name
         $terms = [];
         foreach (CONFIG["custom_taxonomies"] as $taxonomy_slug => $taxonomy_name) $terms[] = $taxonomy_slug;
         $fields_to_inject =  [ 
             //'author_tracking_ids' => get_field( 'amazon_tracking_id', 'user_'.get_current_user_id() ) ,
             'subjects'  => json_encode( get_terms($terms[0], ['hide_empty' => false] )),
             'programs'  => json_encode( get_terms($terms[1], ['hide_empty' => false] )),
             'is_stored' => $this->isStoredPost( $post_id ) ? 'true' : 'false'
         ];
 
         // inject the variables into the html template
         foreach($fields_to_inject as $key => $value)
             $html = str_replace ( '{{'.$key.'}}' , $value , $html );        
 
         // print the block
         header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
         echo $html;        
         die();
    }

    private function isStoredPost( $post_id ) {
        
        $post = get_post($post_id);
        return ( $post->post_type == 'program_stored_link' );        
    }
}