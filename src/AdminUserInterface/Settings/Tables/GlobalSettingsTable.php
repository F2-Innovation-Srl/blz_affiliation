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

        
        $ga_code             = ( $row[ 'ga_code' ]     != null ) ? $row[ 'ga_code' ]     : '';
        $ebay_campain_id     = ( $row[ 'ebay_campain_id' ]     != null ) ? $row[ 'ebay_campain_id' ]     : '';
        $taxonomies          = ( $row[ 'taxonomy' ]    != null ) ? $row[ 'taxonomy' ]    : '';
        $website_ga          = ( $row[ 'website_ga' ]  != null ) ? $row[ 'website_ga' ]  : '';
        $website_trk         = ( $row[ 'website_trk' ] != null ) ? $row[ 'website_trk' ] : '';
        $tracking            = ( $row[ 'tracking' ] != null ) ? $row[ 'tracking' ] : 'SI';

        $this->rows[] =  [
            "Analitics Code" => new Fields\Text( $this->option_name."_ga_code", $ga_code, "text" ),
            "Ebay CampainId" => new Fields\Text( $this->option_name."_ebay_campain_id", $ebay_campain_id, "text" ),
            "Tassonomie di riferimento" => new Fields\Taxonomies( $this->option_name."_taxonomy", serialize( $taxonomies ) ),
            "{website} GA" => new Fields\Text($this->option_name."_website_ga",$website_ga,"text"),
            "{website} TRK_ID" => new Fields\Text($this->option_name."_website_trk",$website_trk,"text"),
            "Abilita tracciamento interno" => new Fields\Text( $this->option_name."_tracking", '', "boolean" ),
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
            'ga_code'            => isset( $_POST[$this->option_name. '_ga_code'  ] ) ? $_POST[$this->option_name. '_ga_code' ]  : ($row[ 'ga_code'  ] ?? ''),
            'ebay_campain_id'    => isset( $_POST[$this->option_name. '_ebay_campain_id'  ] ) ? $_POST[$this->option_name. '_ebay_campain_id' ]  : ($row[ 'ebay_campain_id'  ] ?? ''),
            'taxonomy'           => isset( $_POST[$this->option_name. '_taxonomy' ] ) ? $_POST[$this->option_name. '_taxonomy' ] : ($row[ 'taxonomy' ] ?? ''),
            'website_ga'         => isset( $_POST[ $this->option_name.'_website_ga' ] ) ? $_POST[$this->option_name. '_website_ga' ] : ($row['website_ga'] ?? ''),
            'website_trk'        => isset( $_POST[ $this->option_name.'_website_trk' ] ) ? $_POST[$this->option_name. '_website_trk' ] : ($row['website_trk'] ?? ''),
            'tracking'           => isset( $_POST[ $this->option_name.'_tracking' ] ) ? $_POST[$this->option_name. '_tracking' ] : ($row['tracking'] ?? 'SI')
        ];

        // SET
        update_option( $this->option_name, $row );

        //RETURN
        return $row;
    }
}