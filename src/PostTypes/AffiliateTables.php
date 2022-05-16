<?php

namespace BLZ_AFFILIATION\PostTypes;

/**
 * Crea il post-type delle tabelle di link per affiliazione
 *
 */
class AffiliateTables {

    static function init() {

        // Add the custom columns to the posts post type:
        add_filter( 'init',    [ get_called_class(), 'add_affiliate_tables_post_type'] );

    }

    /**
     * registra il post-type per le tabelle
     *
     * @return void
     */
    static function add_affiliate_tables_post_type() {

        register_post_type('affiliate_table', [

            'public'          => false,
            'show_ui'         => true,
            'capability_type' => 'post',
            'hierarchical'    => false,
            'rewrite'         => [ 'slug' => 'tabella_affiliazione', 'with_front' => false  ],
            'supports'        => [ 'title', 'custom-fields' ],

            'labels' => [
                'name' => 'Tabella Affiliazione',
                'singular_name' => 'tabella_affiliazione',
                'add_new' => 'Aggiungi nuova',
                'add_new_item' => 'Aggiungi nuova tabella',
                'edit' => 'Modifica',
                'edit_item' => 'Modifica tabella',
                'new_item' => 'Nuovo tabella',
                'view' => 'Vedi',
                'view_item' => 'Vedi tabella',
                'search_items' => 'Cerca tabella',
                'not_found' => 'Nessuna tabella trovata',
                'not_found_in_trash' => 'Nessuna tabella trovata nel cestino'
            ]
        ]);

    }
}
