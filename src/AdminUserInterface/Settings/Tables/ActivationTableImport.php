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
    private $title = "Importa da altra tabella di attivazione";
    private $output = [
        "table" => 
            <<<HTML
            <div><h2>{{ title }}</h2></div>
            <table>
               <thead><tr valign="top" style="text-align:left">{{ headings }}</tr></thead>
                <tbody>{{ rows }}</thead>
            </table>
            HTML
    ];
    
	/**
	 * AttivazioneRow constructor.
	 */
	function __construct($option_name) {
        $this->getAndSetRows($option_name);
        $this->rows[] =  [
            "Attivatori" => (new Fields\Import($option_name."_activation_import","","",["current" => $option_name])),
            "Azioni" => (new Fields\Text($option_name."_new",'Importa',"button"))
        ];
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){

        $headings = array_reduce( array_keys( $this->row ), function( $cols, $key ) { 

            $cols .= "<th>$key</th>";
            return $cols;
        } );
        
        foreach ($this->rows as $row)
        $rows = array_reduce( $row, function( $cols, $field ) { 

            $cols .= '<td>' . $field->render() . '</td>';
            return $cols;
        } );
        
        return str_replace([ '', '{{ headings }}', '{{ rows }}' ], [$this->title, $headings, $rows ], $this->output["table"] );       

    }

    private function getAndSetRows($option_name){
        
        if (isset( $_POST[$option_name])  && !empty($_POST[$option_name]) ){
            $activationRows = get_option( $_POST[$option_name]);    
            update_option($option_name,$activationRows);
        }
    }
}