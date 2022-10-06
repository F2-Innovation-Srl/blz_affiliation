<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields\Text;
 
/** 
 * Campi per impostare gli stili
 *
 */
class StyleSettingsTable extends Table{


	protected function getTableFields($row) {

        $this->title = "Custom style"; 
    
        $css_custom_style = ( $row[ 'css_custom_style' ] != null ) ? $row[ 'css_custom_style' ] : '';
        /// compone una riga ( insieme di campi )
        $this->rows[] =  [
            /// inserisce un campo "casella di testo"
            'CSS' => new Text( $this->option_name."_css_custom_style", $css_custom_style, "textarea" ),
            'CSS_AMP' => new Text( $this->option_name."_css_amp_custom_style", $css_custom_style, "textarea" )
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
            'css_custom_style' => isset( $_POST[$this->option_name. '_css_custom_style'  ] ) ? $_POST[$this->option_name. '_css_custom_style' ]  : ( $row[ 'css_custom_style'  ] ?? '' ),  
            'css_amp_custom_style' => isset( $_POST[$this->option_name. '_css_amp_custom_style'  ] ) ? $_POST[$this->option_name. '_css_amp_custom_style' ]  : ( $row[ 'css_amp_custom_style'  ] ?? '' )         
        ];

        // SET
        update_option( $this->option_name, $row );

        //RETURN
        return $row;
    }
}