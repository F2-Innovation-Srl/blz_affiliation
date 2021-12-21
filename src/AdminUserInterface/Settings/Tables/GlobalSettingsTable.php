<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class GlobalSettingsTable
 *
 * @package BLZ_AFFILIATION
 */
class GlobalSettingsTable {

    protected $fields;
    private $rows;

    private $output = [
        "table" => 
            <<<HTML
            <table>
                {{ trs }}  
            </table>
            HTML,
        "headings" => 
            <<<HTML
            <th>{{ th }}</th>
            HTML,
        "trs" => 
            <<<HTML
            <tr valign="top" >{{ tds }}</tr>
            HTML,
        "tds" => 
            <<<HTML
            <td>{{ td }}</td>
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
            new Fields\Text( $option_name."_ga_code", $ga_code, "text" ),
            new Fields\Taxonomy( $option_name."_taxonomy", serialize( $taxonomies ) ),
            new Fields\Text($option_name."_website_ga",$website_ga,"text"),
            new Fields\Text($option_name."_website_trk",$website_trk,"text"),
        ];
    }

	/**
     * Print page if have correct permission
     */
    public function render() {

        foreach( ["Analitics Code","Tassonomie di riferimento","{website} GA","{website}  TRK_ID"] as $label ) 
                    $headings[] = str_replace("{{ th }}",$label, $this->output["headings"]);

        foreach( $this->rows as $row ) {
            foreach( $row as $field ) 
                $tds[] = str_replace("{{ td }}",$field->render(), $this->output["tds"]);

            $trs[] = str_replace("{{ tds }}",implode("",$tds), $this->output["trs"]);
            $tds = [];
        }

        return str_replace( ['{{ trs }}'], [ implode("",$trs)], $this->output["table"] );

       
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