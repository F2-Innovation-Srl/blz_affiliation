<?php

namespace BLZ_AFFILIATION\AdminUserInterface\Buttons;

/*
 *  Class AffiliateLinkButton
 *  
 *  Bottone e UI per la creazione degli shortcode dei link
 */
class AffiliateLinkProgramStoredButton extends Button {

    function __construct() { 
        
        /** 
         * Set the name of the plugin 
         * >> affiliate_link_programs_button.js
         * 
         * and the name of the ajax endpoint to call into plugin
         * >> affiliate_link_programs_action
         */
        parent::__construct('affiliate_link_program_stored_button', 'affiliate_link_program_stored_action');
        
    }


    /**
     * This is the function called from the plugin
     * as endpoint      
     */
    public function ajax_action() {

        // check for rights
        if (! current_user_can('publish_posts'))  { die( __("Vietato") ); } 
        
        // get the template 
        $html = file_get_contents( $this->base_dir .'plugins/dialog-AffiliateLinkProgramStoredButton.html');

        $fields_to_inject =  [ 
            'stored_links' => json_encode( $this->getStoredLinks() ),
            'subjects'  => json_encode( $this->sort_terms(get_terms( 'blz-affiliation-programs', ['hide_empty' => false] ))),
            'pageType'  => json_encode( get_terms( 'blz-affiliation-page-type', ['hide_empty' => false] )),
            'platform'  => json_encode( get_terms( 'blz-affiliation-platform', ['hide_empty' => false] )),
            'type'      => json_encode( get_terms( 'blz-affiliation-type', ['hide_empty' => false] ))
        ];    
         // inject the variables into the html template
         foreach($fields_to_inject as $key => $value) {

            $html = str_replace ( '{{'.$key.'}}' , $value , $html );
        }

        // print the block
        header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
        echo $html;        
        die();
    }

    private function sort_terms ( $terms ) {
        if (is_array($terms) )
            usort($terms, function($a, $b) {return strcmp($a->parent, $b->parent);});
        return $terms;
    }

    private function getStoredLinks() { 

        return array_map( function( $item ) {
            
            return [
                'id' => $item->ID, 
                'title' => $item->post_title, 
                'tax' => [
                    "subjects" => $this->sort_terms(get_the_terms($item->ID,"blz-affiliation-programs")),
                    "pageType" => get_the_terms($item->ID,"blz-affiliation-page-type"),
                    "platform" => get_the_terms($item->ID,"blz-affiliation-platform"),
                    "type" => get_the_terms($item->ID,"blz-affiliation-type")
                ]
        ];

        }, get_posts( [ 'post_type' => 'program_stored_link', 'post_status' => 'publish' , 'posts_per_page' => -1] ));

    }
}