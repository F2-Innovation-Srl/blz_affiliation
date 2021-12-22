<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields\Text;
 
/** 
 * Campi per impostare gli stili
 *
 */
class StyleSettingsTable extends Table{

	/**
	 * 
	 */
	protected function getTableFields($row) {

        $this->title = "Link style"; 
    
        $primary_color = ( $row[ 'primary_color' ] != null ) ? $row[ 'primary_color' ] : '';
        /// compone una riga ( insieme di campi )
        $this->rows[] =  [
            /// inserisce un campo "casella di testo"
            'Colore Primario' => new Text( $this->option_name."_primary_color", $primary_color, "text" ),
        ];
    }


    /**
     * Ritorna una riga
     *
     * @return array
     */
    protected function getAndSetRows( ){
        
        // GET
        $row = get_option( $this->option_name);

        // UPDATE
        $row = [
            'primary_color' => isset( $_POST[$this->option_name. '_primary_color'  ] ) ? $_POST[$this->option_name. '_primary_color' ]  : ( $row[ 'primary_color'  ] ?? '' ),            
        ];

        // SET
        update_option( $this->option_name, $row );

        //RETURN
        return $row;
    }
}