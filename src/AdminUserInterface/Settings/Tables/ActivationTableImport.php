<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ActivationTableImport {

    protected $rows;
    
	/**
	 * AttivazioneRow constructor.
	 */
	function __construct($option_name) {
        $this->getAndSetRows($option_name);
        $this->rows[] =  [
            (new Fields\Import($option_name."_activation_import","","",["current" => $option_name])),
            (new Fields\Text($option_name."_new",'Importa',"button"))
        ];
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        ?>
            <table>
                <?php 
                foreach( $this->rows as $row ) {
                    echo '<tr valign="top">';
                    foreach( $row as $field )  echo "<td>" .$field->render() ."</td>";
                    echo "</tr>";
                }
                ?>
            </table>
    <?php
    }

    private function getAndSetRows($option_name){
        
        if (isset( $_POST[$option_name. '_activation_import'])  && !empty($_POST[$option_name. '_activation_import']) ){
            $activationRows = get_option( $_POST[$option_name. '_activation_import']);    
            update_option($option_name,$activationRows);
        }

      

    }
}