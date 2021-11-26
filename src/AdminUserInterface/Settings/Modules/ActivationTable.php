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
    
        for ($i=0; $i<count($rows); $i++){
            $this->rows[] =  [
                (new Fields\Activator($suffix."attivatore",$rows[$i]["attivatore"])),
                (new Fields\Rule($suffix."regola",$rows[$i]["regola"],$rows[$i]["attivatore"])),
                (new Fields\Text($suffix."ga_val",$rows[$i]["ga_val"],"text")),
                (new Fields\Text($suffix."trk_val",$rows[$i]["trk_val"],"text")),
                (new Fields\Text($suffix."ga_label",$rows[$i]["ga_label"],"text")),
                (new Fields\Text($suffix."trk_label",$rows[$i]["trk_label"],"text")),
                (new Fields\Text($suffix."update",$i,"update")),
                (new Fields\Text($suffix."delete",$i,"delete"))
                
            ];
        }
        // FOR NEW INSERT
        $this->rows[] =  [
            (new Fields\Activator($suffix."attivatore_new","")),
            (new Fields\Rule($suffix."regola_new","")),
            (new Fields\Text($suffix."ga_val_new","","text")),
            (new Fields\Text($suffix."trk_val_new","","text")),
            (new Fields\Text($suffix."ga_label_new","","text")),
            (new Fields\Text($suffix."trk_label_new","","text")),
            (new Fields\Text($suffix."add",'Aggiungi',"add"))
        ];
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
                <?php foreach( $this->rows as $row ) : ?>
                    <tr valign="top"><?php foreach( $row as $field ) echo $field->render(); ?></tr>
                <?php endforeach;?>
            </table>
    <?php
    }
}