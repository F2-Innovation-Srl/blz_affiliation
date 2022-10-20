<?php

namespace BLZ_AFFILIATION\AdminUserInterface\Buttons;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Capability;

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
     * This is the function called from the plugin as endpoint      
     */
    public function ajax_action() {

        // check for rights
        if( !current_user_can( Capability::USER_CAP ) )  { die( __("Vietato") ); } 

      
        
         // get the HTML template 
        $html = file_get_contents( $this->base_dir .'plugins/dialog-AffiliateLinkProgramsButton.html' );
 
        $fields_to_inject =  [ 
            //'author_tracking_ids' => get_field( 'amazon_tracking_id', 'user_'.get_current_user_id() ) ,
            //'subjects'  => json_encode($subjects),
            //'programs'  => json_encode($programs),
            'subjects'  => json_encode( get_terms( 'blz-affiliation-programs',  ['hide_empty' => false] )),
            'pageType'  => json_encode( get_terms( 'blz-affiliation-page-type', ['hide_empty' => false] )),
            'platform'  => json_encode( get_terms( 'blz-affiliation-platform',  ['hide_empty' => false] )),
            'type'      => json_encode( get_terms( 'blz-affiliation-type',      ['hide_empty' => false] )),
            'is_stored' => $this->isStoredPost() ? 'true' : 'false'
        ];
         
         // inject the variables into the html template
         foreach($fields_to_inject as $key => $value) {

            $html = str_replace ( '{{'.$key.'}}' , $value , $html );        
         }

         // print the block
         header('Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option('blog_charset'));
         echo $html;        
         die();
    }

    /**
     * Verifica che il post sia uno stored link
     *     
     * @return boolean
     */
    private function isStoredPost() {

        ///  tenta di leggere il valore del post_type (passato come )
        $post_type = isset( $_GET[ "post_type" ]  ) ? $_GET[ "post_type" ]: '';

        if ( empty( $post_type ) ) {

            /// tenta di prendere il valore del post id
            $post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : 0;
            
            if ($post_id) {

                $post = get_post( $post_id );
                $post_type = $post->post_type;
            }            
        }
        
        return $post_type == 'program_stored_link';
    }
}