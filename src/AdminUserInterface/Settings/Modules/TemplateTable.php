<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class TemplateTable {

    protected $fields;
    private $option_name;
    private $current;
	/**
	 * AttivazioneRow constructor.
	 */
	function __construct($option_name,$current) {
        $this->option_name = $option_name;
        $this->current = $current;
        
        $this->$fields = [
            "Activator" => (new Fields\Activator($data["activator"])),
            "Rule" => (new Fields\Rule($data["rule"],$data["activator"]))
        ];
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        ?>
       <div><h2>Template</h2></div>
        <table>
            <tr valign="top" style="text-align:left">
               <td>GA EVENT</td>
               <td><input type="text" readonly value="<?php echo $this->current["tab"]["ga_event_template"];?>"></td>
            </tr>
            <tr valign="top" style="text-align:left">
               <td>TRACKING ID</td>
               <td><input type="text" readonly value="<?php echo $this->current["tab"]["tracking_id"];?>"></td>
            </tr>
        </table>
    <?php
       
    }
}