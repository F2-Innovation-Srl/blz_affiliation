<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Campi per la form di configurazione di base
 *
 * @package BLZ_AFFILIATION
 */
class ConfigSettingsTable extends Table {

    public function __construct( $option_name, $current = null, $title = ''  ) {

        parent::__construct( 'blz-affiliation-basic' , $current, $title );        
    }

	protected function getTableFields( $rows) {

        $config      = empty( $rows ) ? '' : stripslashes( $rows['config'] );
        $author_caps = empty( $rows ) ? '' : ( isset( $rows['author_capabilities'] ) ? $rows['author_capabilities'] : '' );
        $post_types  = empty( $rows ) ? '' : ( isset( $rows['post_types'] )          ? $rows['post_types']          : '' );
   
        $this->rows[] =  [
            "Custom Author Capabilities" => new Fields\Text( $this->option_name."_author_capabilities", $author_caps, "text" ),
            "Custom Post Types"          => new Fields\Text( $this->option_name."_post_types", $post_types, "text" ),
            "Configuration"              => new Fields\Text( $this->option_name."_config", stripslashes( $config ), "textarea" )            
        ];
    }


    /**
     * Ritorna una riga
     *
     * @param [type] $this->option_name
     * @return array
     */
    protected function getAndSetRows(){
        
        // GET
        $rows = get_option( $this->option_name );

        $config      = isset( $rows[ 'config'               ] ) ? stripslashes( $rows[ 'config' ]) :'';
        $author_caps = isset( $rows[ 'author_capabilities'  ]) ? $rows[ 'author_capabilities'  ] : '';
        $post_types  = isset( $rows[ 'post_types'           ]) ? $rows[ 'post_types' ] : '';

        // UPDATE
        $rows = [
            'config'              => isset( $_POST[$this->option_name. '_config'              ] ) ? $_POST[$this->option_name. '_config' ]               : $config ,
            'post_types'          => isset( $_POST[$this->option_name. '_post_types'          ] ) ? $_POST[$this->option_name. '_post_types' ]           : $post_types,
            'author_capabilities' => isset( $_POST[$this->option_name. '_author_capabilities' ] ) ? $_POST[$this->option_name. '_author_capabilities' ]  : $author_caps
        ];

        // SET
        update_option( $this->option_name, $rows );


        //RETURN
        return $rows;
    }
}