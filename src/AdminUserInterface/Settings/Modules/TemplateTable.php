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
    private $current;
    private $row;
	/**
	 * AttivazioneRow constructor.
	 */
	function __construct($option_name,$current) {
        $this->current = $current;

        $row = $this->getAndSetRow($option_name);
        $hiddenGA = (empty($this->current["marketplace"]["ga_event_template"]) ) ? "hidden" : "text"; 
        $hiddenTrack = (empty($this->current["marketplace"]["tracking_id"]) ) ? "hidden" : "text"; 
       
        $this->row =  [
            (new Fields\Text($option_name."_active",$row["active"],"boolean")),
            (new Fields\Text($option_name."_ga_default",$row["ga_default"],"text")),
            (new Fields\Text($option_name."_trk_default",$row["trk_default"],"text")),
            (new Fields\Text('',"Aggiorna valori","button"))
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
                <th>Attivo</th>   <th>Valore TRK_ID Default</th>  <th>Valore GA Default</th>  
            </tr>
            <tr valign="top" style="text-align:left">
            <?php foreach( $this->row as $field )  echo "<td>" .$field->render() ."</td>"; ?>
            </tr>
        </table>
        <?php if (!empty($this->current["marketplace"]["ga_event_template"]) || !empty($this->current["marketplace"]["tracking_id"]) ) :?>
       <div><h2>Template</h2></div>
        <table>
            <?php if (!empty($this->current["marketplace"]["ga_event_template"])) : ?>
            <tr valign="top" style="text-align:left">
               <th>GA EVENT</th> 
               <td><input style="width:450px" type="text" readonly value="<?php echo $this->current["marketplace"]["ga_event_template"];?>"></td>
            </tr>
            <?php endif;
            if (!empty($this->current["marketplace"]["tracking_id"])) : ?>
            <tr valign="top" style="text-align:left">
               <th>TRACKING ID</th>
               <td><input style="width:450px" type="text" readonly value="<?php echo $this->current["marketplace"]["tracking_id"];?>"></td>
            </tr>
            <?php endif;?>
        </table>
        <?php endif;?>
        
    <?php
       
    }

    private function getAndSetRow($option_name){
        
        //GET
        $row = get_option($option_name."_settings");
        echo "<pre>";

        //UPDATE
        $row = [
            'active' => isset( $_POST[$option_name. '_active' ] ) ? $_POST[$option_name. '_active' ] : (isset($row['active']) ? $row['active'] : "true"),
            'ga_default' => isset( $_POST[ $option_name.'_ga_default' ] ) ? $_POST[ $option_name.'_ga_default' ] : ($row['ga_default'] ?? ''),
            'trk_default' => isset( $_POST[ $option_name.'_trk_default' ] ) ? $_POST[$option_name. '_trk_default' ] : ($row['trk_default'] ?? '')
        ];

        //SET
        update_option($option_name."_settings",$row);
        //RETURN
        return $row;

    }
}