<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;
 
/**
 * Class GeneralSettingsTable
 *
 * @package BLZ_AFFILIATION
 */
class GeneralSettingsTable {

    protected $fields;
    private $row;
	/**
	 * GeneralSettingsTable constructor.
	 */
	function __construct($option_name) {

        $row = $this->getAndSetRow($option_name);
        $this->row =  [
            (new Fields\Text($option_name."_ga_code".$i,$row["active"],"text"))
        ];
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        ?>
        <div><h2>Settings</h2></div>
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
        $row = get_option($option_name."_settings");
        echo "<pre>";

        //UPDATE
        $row = [
            'ga_code' => isset( $_POST[$option_name. '_ga_code' ] ) ? $_POST[$option_name. '_ga_code' ] : (isset($row['ga_code']) ? $row['ga_code'] : "true")
        ];

        //SET
        update_option($option_name."_settings",$row);
        //RETURN
        return $row;

    }
}