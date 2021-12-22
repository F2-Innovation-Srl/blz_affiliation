<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class TemplateTable {

    protected $fields;
    private $current;
    private $rows;
    private $output = [
        "table" =>
        <<<HTML
            <div><h2>Template</h2></div>
            <table>
                {{ rows }}
            </table>
        HTML,
        "rows" =>
        <<<HTML
            <tr valign="top" style="text-align:left">
                <th>{{ title }}</th> 
                <td><input style="width:450px" type="text" readonly value="{{ field }}"></td>
            </tr>
        HTML
    ];


	/**
	 * AttivazioneRow constructor.
	 */
	function __construct($option_name,$current) {
        $this->current = $current;
        
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        $rows = [];
        if (!empty($this->current["marketplace"]["ga_event_template"]) || !empty($this->current["marketplace"]["tracking_id"]) ) {
            if (!empty($this->current["marketplace"]["ga_event_template"])) 
                $rows[] =  str_replace(['{{ title }}','{{ field }}'],['GA EVENT',$this->current["marketplace"]["ga_event_template"]],$this->output["field"]);
            if (!empty($this->current["marketplace"]["tracking_id"])) 
                $rows[] =  str_replace(['{{ title }}','{{ field }}'],['TRACKING ID',$this->current["marketplace"]["tracking_id"]], $this->output["field"]);
        
            return str_replace('{{ rows }}', implode("",$rows), $this->output["table"]);
        }
       
    }

}