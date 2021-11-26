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
            (new Fields\Text($option_name."_ga_code",$row["active"],"text"))
        ];
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        ?>
        <table>
            <tr valign="top" style="text-align:left">
                <th>Analitics Code</th>         
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
            'ga_code' => isset( $_POST[$option_name. '_ga_code' ] ) ? $_POST[$option_name. '_ga_code' ] : isset($row['ga_code'])
        ];

        //SET
        update_option($option_name,$row);
        //RETURN
        return $row;

    }
}