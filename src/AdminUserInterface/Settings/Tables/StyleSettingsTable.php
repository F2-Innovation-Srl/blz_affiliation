<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields\Text;
 
/** 
 * Campi per impostare gli stili
 *
 */
class StyleSettingsTable {

    private $rows;
    private $title = "Link style"; 
    private $output = [
        "table" => 
            <<<HTML
            <div><h2>{{ title }}</h2></div>
            <table>
               <thead><tr valign="top" style="text-align:left">{{ headings }}</tr></thead>
                <tbody>{{ rows }}</thead>
            </table>
            HTML
    ];

	/**
	 * 
	 */
	function __construct( $option_name ) {
        $row = $this->getAndSetRow( $option_name );

        $primary_color = ( $row[ 'primary_color' ] != null ) ? $row[ 'primary_color' ] : '';
        /// compone una riga ( insieme di campi )
        $this->rows[] =  [
            /// inserisce un campo "casella di testo"
            'Colore Primario' => new Text( $option_name."_primary_color", $primary_color, "text" ),
        ];
    }

	/**
     * Print page if have correct permission
     */
    public function render() {

        $headings = array_reduce( array_keys( $this->row ), function( $cols, $key ) { 

            $cols .= "<th>$key</th>";
            return $cols;
        } );

        $rows = array_reduce( $this->rows, function( $cols, $field ) { 

            $cols .= '<td>' . $field->render() . '</td>';
            return $cols;
        } );
        
        return str_replace([ '{{ title }}', '{{ headings }}', '{{ rows }}' ], [ $this->title, $headings, $rows ], $this->output["table"] );       
    }

    /**
     * Ritorna una riga
     *
     * @param [type] $option_name
     * @return array
     */
    private function getAndSetRow( $option_name ){
        
        // GET
        $row = get_option( $option_name);

        // UPDATE
        $row = [
            'primary_color' => isset( $_POST[$option_name. '_primary_color'  ] ) ? $_POST[$option_name. '_primary_color' ]  : ( $row[ 'primary_color'  ] ?? '' ),            
        ];

        // SET
        update_option( $option_name, $row );

        //RETURN
        return $row;
    }
}