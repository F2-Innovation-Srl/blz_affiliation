<?php

namespace BLZ_AFFILIATION\core\CustomPostTypes;

/**
 * Crea il post-type delle tabelle di link per affiliazione
 *
 */
class AffiliateLinkProgamStored {

    static function init() {

        // Add the custom columns to the posts post type:
        add_filter( 'init',    [ get_called_class(), 'add_affiliate_link_program_stored_post_type'] );

    }

    /**
     * registra il post-type per le tabelle
     *
     * @return void
     */
    static function add_affiliate_link_program_stored_post_type() {

        register_post_type('program_stored_link', [

            'public'          => false,
            'show_ui'         => true,
            'capability_type' => 'post',
            'hierarchical'    => false,
            'rewrite'         => [ 'slug' => 'link_program', 'with_front' => false  ],
            'supports'        => [ 'title', 'editor', 'custom-fields' ],

            'labels' => [
                'name' => 'Link Programma Affiliazione',
                'singular_name' => 'link_programma_affiliazione',
                'add_new' => 'Aggiungi nuovo',
                'add_new_item' => 'Aggiungi nuovo link',
                'edit' => 'Modifica',
                'edit_item' => 'Modifica link',
                'new_item' => 'Nuovo link',
                'view' => 'Vedi',
                'view_item' => 'Vedi link',
                'search_items' => 'Cerca link',
                'not_found' => 'Nessun link trovato',
                'not_found_in_trash' => 'Nessun link trovato nel cestino'
            ]
        ]);
        
    }
}
