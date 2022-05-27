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
         $post_id = 0;
         if (isset($_GET['post']))
            $post_id = intval($_GET['post'] );
         
         // get the template 
         $html = file_get_contents( $this->base_dir .'plugins/dialog-AffiliateLinkProgramsButton.html');
 

        $rows = get_option("blz-affiliation-program");
        
        $subjects = ($rows) ? array_map( function ( $row, $idx  ) {
            
             return [
                'slug' => $row['subject_slug'],
                'name' => $row['subject_name'],
            ];
        }, $rows, array_keys($rows) ) : [];
        //print_r($subjects);exit;
        $subjects = $this->unique_multidim_array($subjects, 'slug');
        $programs = ($rows) ? array_map( function ( $row, $idx  ) {
            return [
               'slug' => $row['program_slug'],
               'name' => $row['program_name'],
               'parent_slug' => $row['subject_slug']
           ];
       }, $rows, array_keys($rows) ) : [];
         $fields_to_inject =  [ 
             //'author_tracking_ids' => get_field( 'amazon_tracking_id', 'user_'.get_current_user_id() ) ,
             'subjects'  => json_encode($subjects),
             'programs'  => json_encode($programs),
             'is_stored' => $this->isStoredPost( $post_id ) ? 'true' : 'false'
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

    private function isStoredPost( $post_id ) {
        if (isset($_GET['post'])){
            $post = get_post($post_id);
            return ( $post->post_type == 'program_stored_link' );  
        }else{
            if ($_GET["?post_type"] == 'program_stored_link' || $_GET["post_type"] == 'program_stored_link') return true;
            return false;   
        }
              
    }

    private function unique_multidim_array($array,$property) {
        $tempArray = array_unique(array_column($array, $property));
        $moreUniqueArray = array_values(array_intersect_key($array, $tempArray));
        return $moreUniqueArray;
    }
      
      

}