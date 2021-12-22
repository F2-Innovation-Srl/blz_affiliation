<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class GlobalSettingsTable
 *
 * @package BLZ_AFFILIATION
 */
class GlobalSettingsTable extends Table{
    

	protected function getTableFields($row) {

        $this->title = "Global Settings";

   
        $ga_code     = ( $row[ 'ga_code' ]     != null ) ? $row[ 'ga_code' ]     : '';
        $taxonomies  = ( $row[ 'taxonomy' ]    != null ) ? $row[ 'taxonomy' ]    : '';
        $website_ga  = ( $row[ 'website_ga' ]  != null ) ? $row[ 'website_ga' ]  : '';
        $website_trk = ( $row[ 'website_trk' ] != null ) ? $row[ 'website_trk' ] : '';

        $this->rows[] =  [
            "Analitics Code" => new Fields\Text( $this->option_name."_ga_code", $ga_code, "text" ),
            "Tassonomie di riferimento" => new Fields\Taxonomy( $this->option_name."_taxonomy", serialize( $taxonomies ) ),
            "{website} GA" => new Fields\Text($this->option_name."_website_ga",$website_ga,"text"),
            "{website} TRK_ID" => new Fields\Text($this->option_name."_website_trk",$website_trk,"text"),
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
        $row = get_option( $this->option_name );

        // UPDATE
        $row = [
            'ga_code'     => isset( $_POST[$this->option_name. '_ga_code'  ] ) ? $_POST[$this->option_name. '_ga_code' ]  : ($row[ 'ga_code'  ] ?? ''),
            'taxonomy'    => isset( $_POST[$this->option_name. '_taxonomy' ] ) ? $_POST[$this->option_name. '_taxonomy' ] : ($row[ 'taxonomy' ] ?? ''),
            'website_ga'  => isset( $_POST[ $this->option_name.'_website_ga' ] ) ? $_POST[$this->option_name. '_website_ga' ] : ($row['website_ga'] ?? ''),
            'website_trk' => isset( $_POST[ $this->option_name.'_website_trk' ] ) ? $_POST[$this->option_name. '_website_trk' ] : ($row['website_trk'] ?? '')
        ];

        // SET
        update_option( $this->option_name, $row );

        //RETURN
        return $row;
    }
}