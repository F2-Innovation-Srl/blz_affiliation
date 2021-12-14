<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;
 
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
            (new Fields\Text($option_name."_activation_import","")),
            (new Fields\Text($option_name."_new",'Aggiungi',"button"))
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
                print_r($_POST[$option_name. '_activation_import']);exit;
        }

    
        //SET
       // update_option($option_name,$activationRows);

      

    }
}