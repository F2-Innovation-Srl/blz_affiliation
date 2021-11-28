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
	function __construct($option_name) {

        $row = $this->getAndSetRow($option_name);
        $this->row =  [
            (new Fields\Text($option_name."_ga_code",$row["ga_code"],"text")),
            (new Fields\Taxonomy($option_name."_taxonomy",$row["taxonomy"])),
            (new Fields\Text('',"Aggiorna valori","button"))
        ];
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        ?>
        <table>
            <tr valign="top" style="text-align:left">
                <th>Analitics Code</th><th>Tassonomia di riferimento</th>           
                <?php foreach( $this->row as $field )  echo "<td>" .$field->render() ."</td>"; ?>
            </tr>
        </table>
    <?php
       
    }

    private function getAndSetRow($option_name){
        
        //GET
        $row = get_option($option_name);
        
        //UPDATE
        $row = [
            'ga_code' => isset( $_POST[$option_name. '_ga_code' ] ) ? $_POST[$option_name. '_ga_code' ] : $row['ga_code'],
            'taxonomy' => isset( $_POST[$option_name. '_taxonomy' ] ) ? $_POST[$option_name. '_taxonomy' ] : $row['taxonomy']
        ];

        //SET
        update_option($option_name,$row);
        //RETURN
        return $row;

    }
}