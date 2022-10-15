<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields\Text;
 
/** 
 * Campi per impostare gli stili
 *
 */
class DisclaimerTable extends Table{


	protected function getTableFields($row) {

        $this->title = "Disclaimer "; 
    
        $disclaimer = ( $row[ 'disclaimer' ] != null ) ? $row[ 'disclaimer' ] : '';
        /// compone una riga ( insieme di campi )
        $this->rows[] =  [
            /// inserisce un campo "casella di testo"
            'disclaimer (per skinnare impostare le proprietà alla classe css "blz_affiliation_disclaimer")' => new Text( $this->option_name."_disclaimer", $disclaimer, "text" ),
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
            'disclaimer' => isset( $_POST[$this->option_name. '_disclaimer'  ] ) ? $_POST[$this->option_name. '_disclaimer' ]  : ( $row[ 'disclaimer'  ] ?? '' ),            
        ];

        // SET
        update_option( $this->option_name, $row );

        //RETURN
        return $row;
    }
}