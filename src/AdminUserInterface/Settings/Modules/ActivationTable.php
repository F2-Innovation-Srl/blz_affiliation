<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ActivationTable {

    protected $rows;
    
	/**
	 * AttivazioneRow constructor.
	 */
	function __construct($suffix,$rows) {
    
        foreach ($rows as $row){

            $this->$rows[] =  [
                (new Activator($suffix."attivatore",$row["attivatore"]))->render(),
                (new Rule($suffix."regola",$row["regola"],$row["attivatore"]))->render(),
                (new Text($suffix."ga_val",$row["ga_val"],"text"))->render(),
                (new Text($suffix."trk_val",$row["trk_val"],"text"))->render(),
                (new Text($suffix."ga_label",$row["ga_label"],"text"))->render(),
                (new Text($suffix."trk_label",$row["trk_label"],"text"))->render()
            ];
        }
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        ?>
        <div><h2>Tabella di attivazione</h2></div>
            <table>
                <tr valign="top" style="text-align:left">
                    <th>Attivatore</th><th>Regola</th><th>Valore GA</th><th>Valore TRK_ID</th><th>Label GA</th><th>Label TRK_ID</th><th>&nbsp;</th>                       
                </tr>
                <tr valign="top">    
                <?php foreach( $rows as $row ) echo $row; ?>
                <td><?php submit_button('Aggiungi', 'primary', 'submit', false ); ?></td>                    
                </tr>
            </table>
    <?php
    }
}