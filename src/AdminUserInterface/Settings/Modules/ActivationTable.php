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
    protected $current;
    
	/**
	 * AttivazioneRow constructor.
	 */
	function __construct($option_name,$current) {
        $this->current = $current;
        $rows = $this->getAndSetRows($option_name);

        for ($i=0; $i<count($rows); $i++){
            $this->rows[] =  [
                (new Fields\Activator($option_name."_attivatore".$i,$rows[$i]["attivatore"])),
                (new Fields\Rule($option_name."_regola".$i,$rows[$i]["regola"],$rows[$i]["attivatore"])),
                (new Fields\Label($option_name."_ga_label".$i,$rows[$i]["ga_label"],"GA",$current)),
                (new Fields\Text($option_name."_ga_val".$i,$rows[$i]["ga_val"],"text")),
                (new Fields\Label($option_name."_trk_label".$i,$rows[$i]["trk_label"],"TRACK",$current)),
                (new Fields\Text($option_name."_trk_val".$i,$rows[$i]["trk_val"],"text")),
                (new Fields\Text($i,"Update","button")),
                (new Fields\Text($i,"Delete","button"))
            ];
        }
        // FOR NEW INSERT
        $this->rows[] =  [
            (new Fields\Activator($option_name."_attivatore_new","")),
            (new Fields\Rule($option_name."_regola_new","")),
            (new Fields\Label($option_name."_ga_label_new","","GA")),
            (new Fields\Text($option_name."_ga_val_new","","text")),
            (new Fields\Label($option_name."_trk_label_new","","TRACK",$current)),
            (new Fields\Text($option_name."_trk_val_new","","text")),
            (new Fields\Text($option_name."_new",'Aggiungi',"button",$current)),
            (new Fields\Text("hidden_for_delete",'',"hidden"))
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
        
        //GET
        $activationRows = get_option($option_name);

        //UPDATE
        $activationRows = ($activationRows) ? array_map( function ( $activationRow, $idx  )  use ($option_name)  {

            return [
                'id' => $idx,
                'attivatore' => isset( $_POST[$option_name. '_attivatore'.$idx ] ) ? $_POST[$option_name. '_attivatore'.$idx ] : $activationRow['attivatore'],
                'regola' => isset( $_POST[ $option_name.'_regola'.$idx ] ) ? $_POST[ $option_name.'_regola'.$idx ] : $activationRow['regola'],
                'ga_label' => isset( $_POST[ $option_name.'_ga_label'.$idx ] ) ? $_POST[ $option_name.'_ga_label'.$idx ] : $activationRow['ga_label'],
                'ga_val' => isset( $_POST[ $option_name.'_ga_val'.$idx ] ) ? $_POST[$option_name. '_ga_val'.$idx ] : $activationRow['ga_val'],
                'trk_val' => isset( $_POST[ $option_name.'_trk_val'.$idx ] ) ? $_POST[ $option_name.'_trk_val'.$idx ] : $activationRow['trk_val'],
                'trk_label' => isset( $_POST[ $option_name.'_trk_label'.$idx ] ) ? $_POST[$option_name. '_trk_label'.$idx ] : $activationRow['trk_label'],
            ];
        
        }, $activationRows, array_keys($activationRows) ) : [];

        //DELETE
        $id_to_delete = $_POST['hidden_for_delete'];
        if ($id_to_delete != "" && $id_to_delete != null){
            $activationRows = array_values(array_filter($activationRows,function($row) use($id_to_delete) {
                    return $row["id"] != $id_to_delete;
            }));  
        }

        //INSERT 
        if( !empty( $_POST[$option_name.'_attivatore_new'] ) ) {

            $activationRows[] = [
                'attivatore' => $_POST[$option_name.'_attivatore_new'],
                'regola' => $_POST[$option_name.'_regola_new'],
                'ga_label' => $_POST[$option_name.'_ga_label_new'],
                'ga_val' => $_POST[$option_name.'_ga_val_new'],
                'trk_label' => $_POST[$option_name.'_trk_label_new'],
                'trk_val' => $_POST[$option_name.'_trk_val_new']
                
            ];
        }

        //SET
        update_option($option_name,$activationRows);

        //RETURN
        return $activationRows;

    }
}