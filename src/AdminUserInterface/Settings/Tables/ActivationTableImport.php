<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ActivationTableImport {

    private $rows;

    private $output = [
        "table" => 
            <<<HTML
            <table>
                {{ trs }}  
            </table>
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

        foreach( $this->rows as $row ) {
            foreach( $row as $field ) 
                $tds[] = str_replace("{{ td }}",$field->render(), $this->output["tds"]);

            $trs[] = str_replace("{{ tds }}",implode("",$tds), $this->output["trs"]);
            $tds = [];
        }

        return str_replace( ['{{ trs }}'], [ implode("",$trs)], $this->output["table"] );
    }

    private function getAndSetRows($option_name){
        
        if (isset( $_POST[$option_name. '_activation_import'])  && !empty($_POST[$option_name. '_activation_import']) ){
            $activationRows = get_option( $_POST[$option_name. '_activation_import']);    
            update_option($option_name,$activationRows);
        }
    }
}