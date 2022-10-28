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
        $author_caps = empty( $rows ) ? '' : $rows['author_capabilities'];
   
        $this->rows[] =  [
            "Custom Author Capabilities" => new Fields\Text( $this->option_name."_author_capabilities", $author_caps, "text" ),
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

        $config      = isset( $rows[ 'config'  ] ) ? stripslashes( $rows[ 'config' ]) :'';
        $author_caps = isset( $rows[ 'author_capabilities'  ]) ? $rows[ 'author_capabilities'  ] : '';

        // UPDATE
        $rows = [
            'config'              => isset( $_POST[$this->option_name. '_config'  ] )             ? $_POST[$this->option_name. '_config' ]               : $config ,
            'author_capabilities' => isset( $_POST[$this->option_name. '_author_capabilities' ] ) ? $_POST[$this->option_name. '_author_capabilities' ]  : $author_caps
        ];

        // SET
        update_option( $this->option_name, $rows );


        //RETURN
        return $rows;
    }
}