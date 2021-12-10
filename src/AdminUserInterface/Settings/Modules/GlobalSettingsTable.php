<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;
 
/**
 * Class GlobalSettingsTable
 *
 * @package BLZ_AFFILIATION
 */
class GlobalSettingsTable {

    protected $fields;
    private $row;

	/**
	 * GlobalSettingsTable constructor.
	 */
	function __construct( $option_name ) {

        $row = $this->getAndSetRow( $option_name );
        
        $ga_code =  ( $row[ 'ga_code' ]  != null ) ? $row[ 'ga_code' ] : '';
        $taxonomies = ( $row[ 'taxonomy' ] != null ) ? $row[ 'taxonomy' ] : '';

        $this->row =  [
            (new Fields\Text( $option_name."_ga_code", $ga_code, "text" )),
            (new Fields\Taxonomy( $option_name."_taxonomy", serialize( $taxonomies ) )),
            (new Fields\Text($option_name."_website_ga",$row["website_ga"],$hiddenGA)),
            (new Fields\Text($option_name."_website_trk",$row["website_trk"],$hiddenTrack)),
        ];
    }

	/**
     * Print page if have correct permission
     */
    public function render() {
        ?>
        <table>
            <tr valign="top" style="text-align:left">
                <th>Analitics Code</th><th>Tassonomie di riferimento</th> <th>{website} GA </th><th>{website}  TRK_ID</th>  
            </tr>
            <tr valign="top" style="text-align:left">     
                <?php foreach( $this->row as $field )  echo "<td>" .$field->render() ."</td>"; ?>
            </tr>
        </table>
    <?php
       
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