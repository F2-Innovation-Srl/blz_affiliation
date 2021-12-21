<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields\Text;
 
/** 
 * Campi per impostare gli stili
 *
 */
class StyleSettingsTable {

    protected $fields;
    private   $row;

	/**
	 * 
	 */
	function __construct( $option_name ) {

        $row = $this->getAndSetRow( $option_name );

        $primary_color = ( $row[ 'primary_color' ] != null ) ? $row[ 'primary_color' ] : '';
    
        /// compone una riga ( insieme di campi )
        $this->row =  [
            /// inserisce un campo "casella di testo"
            'Colore Primario' => new Text( $option_name."_primary_color", $primary_color, "text" ),
        ];
    }

	/**
     * Print page if have correct permission
     */
    public function render() {

        $table = <<<HTML
            <table>
                <thead><tr valign="top" style="text-align:left">{{ headings }}</tr></thead>
                <tbody>{{ rows }}</thead>
            </table>
        HTML;
        
        $headings = array_reduce( array_keys( $this->row ), function( $cols, $key ) { 

            $cols .= "<th>$key</th>";
            return $cols;
        } );

        $rows = array_reduce( $this->row, function( $cols, $field ) { 

            $cols .= '<td>' . $field->render() . '</td>';
            return $cols;
        } );

        return str_replace([ '{{ headings }}', '{{ rows }}' ], [ $headings, $rows ], $table );       
    }

    /**
     * Ritorna una riga
     *
     * @param [type] $option_name
     * @return array
     */
    private function getAndSetRow( $option_name ){
        
        // GET
        $row = get_option( $option_name ."-css");

        // UPDATE
        $row = [
            'primary_color' => isset( $_POST[$option_name. '_primary_color'  ] ) ? $_POST[$option_name. '_primary_color' ]  : ( $row[ 'primary_color'  ] ?? '' ),            
        ];

        // SET
        update_option( $option_name."-css", $row );

        //RETURN
        return $row;
    }
}