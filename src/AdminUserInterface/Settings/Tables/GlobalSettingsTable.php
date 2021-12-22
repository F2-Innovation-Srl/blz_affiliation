<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class GlobalSettingsTable
 *
 * @package BLZ_AFFILIATION
 */
class GlobalSettingsTable {

    private $rows;
    private $title = "Global Settings";
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
	 * GlobalSettingsTable constructor.
	 */
	function __construct( $option_name ) {

        $row = $this->getAndSetRow( $option_name );
        
        $ga_code     = ( $row[ 'ga_code' ]     != null ) ? $row[ 'ga_code' ]     : '';
        $taxonomies  = ( $row[ 'taxonomy' ]    != null ) ? $row[ 'taxonomy' ]    : '';
        $website_ga  = ( $row[ 'website_ga' ]  != null ) ? $row[ 'website_ga' ]  : '';
        $website_trk = ( $row[ 'website_trk' ] != null ) ? $row[ 'website_trk' ] : '';

        $this->rows[] =  [
            "Analitics Code" => new Fields\Text( $option_name."_ga_code", $ga_code, "text" ),
            "Tassonomie di riferimento" => new Fields\Taxonomy( $option_name."_taxonomy", serialize( $taxonomies ) ),
            "{website} GA" => new Fields\Text($option_name."_website_ga",$website_ga,"text"),
            "{website} TRK_ID" => new Fields\Text($option_name."_website_trk",$website_trk,"text"),
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

        $rows = array_reduce( $this->row, function( $cols, $field ) { 

            $cols .= '<td>' . $field->render() . '</td>';
            return $cols;
        } );
        
        return str_replace([ '{{ title }}', '{{ headings }}', '{{ rows }}' ], [$this->title, $headings, $rows ], $this->output["table"] );       

    }

    /**
     * Ritorna una riga
     *
     * @param [type] $option_name
     * @return array
     */
    private function getAndSetRow($option_name){
        
        // GET
        $row = get_option( $option_name );

        // UPDATE
        $row = [
            'ga_code'     => isset( $_POST[$option_name. '_ga_code'  ] ) ? $_POST[$option_name. '_ga_code' ]  : ($row[ 'ga_code'  ] ?? ''),
            'taxonomy'    => isset( $_POST[$option_name. '_taxonomy' ] ) ? $_POST[$option_name. '_taxonomy' ] : ($row[ 'taxonomy' ] ?? ''),
            'website_ga'  => isset( $_POST[ $option_name.'_website_ga' ] ) ? $_POST[$option_name. '_website_ga' ] : ($row['website_ga'] ?? ''),
            'website_trk' => isset( $_POST[ $option_name.'_website_trk' ] ) ? $_POST[$option_name. '_website_trk' ] : ($row['website_trk'] ?? '')
        ];

        // SET
        update_option( $option_name, $row );

        //RETURN
        return $row;
    }
}