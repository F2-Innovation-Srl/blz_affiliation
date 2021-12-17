<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ActivationTable {

    protected $rows;
    protected $current;
    protected $option_name;
	/**
	 * AttivazioneRow constructor.
	 */
	function __construct($option_name,$current) {
        $this->current = $current;
        $this->option_name = $option_name;
        $rows = $this->getAndSetRows($option_name);
        $hiddenGA = (empty($this->current["marketplace"]["ga_event_template"]) ) ? "hidden" : "text"; 
        $hiddenTrack = (empty($this->current["marketplace"]["tracking_id"]) ) ? "hidden" : "text"; 

        for ($i=0; $i<count($rows); $i++){
            $this->rows[] =  [
                new Fields\Arrow($i,"","UP"),
                new Fields\Arrow($i,"","DOWN"),
                new Fields\Activator($option_name."_attivatore".$i,$rows[$i]["attivatore"]),
                new Fields\Rule($option_name."_regola".$i,$rows[$i]["regola"],$rows[$i]["attivatore"]),
                new Fields\Label($option_name."_ga_label".$i,$rows[$i]["ga_label"],"GA",$current),
                new Fields\Text($option_name."_ga_val".$i,$rows[$i]["ga_val"],$hiddenGA),
                new Fields\Label($option_name."_trk_label".$i,$rows[$i]["trk_label"],"TRK_ID",$current),
                new Fields\Text($option_name."_trk_val".$i,$rows[$i]["trk_val"],$hiddenTrack),
                new Fields\Text($i,"Update","button"),
                new Fields\Text($i,"Delete","button",["hidden_field" => $option_name])
            ];
        }
        // FOR NEW INSERT
        $this->rows[] =  [
            new Fields\Text($option_name."_hidden_for_up","","hidden"),
            new Fields\Text($option_name."_hidden_for_down","","hidden"),
            new Fields\Activator($option_name."_attivatore_new",""),
            new Fields\Rule($option_name."_regola_new",""),
            new Fields\Label($option_name."_ga_label_new","","GA",$current),
            new Fields\Text($option_name."_ga_val_new","",$hiddenGA),
            new Fields\Label($option_name."_trk_label_new","","TRK_ID",$current),
            new Fields\Text($option_name."_trk_val_new","",$hiddenTrack),
            new Fields\Text($option_name."_new",'Aggiungi',"button"),
            new Fields\Text($option_name."_hidden_for_delete",'',"hidden")
        ];
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        ?>
        <div><h2 id="tabella" name="tabella">Tabella di attivazione</h2></div>
            <?php (new ActivationTableImport($this->option_name))->render();  ?>
            <table>
                <tr valign="top" style="text-align:left">
                    <th>&nbsp;</th><th>&nbsp;</th>
                    <th>Attivatore</th><th>Regola</th>
                    <?php if (!empty($this->current["marketplace"]["ga_event_template"])) : ?>
                    <th>Label GA</th><th>Valore GA</th>
                    <?php endif;?>   
                    <?php if (!empty($this->current["marketplace"]["tracking_id"])) : ?>
                    <th>Label TRK_ID</th><th>Label TRK_ID</th>
                    <?php endif;?>   
                    <th>&nbsp;</th>                       
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
                'attivatore' => isset( $_POST[$option_name. '_attivatore'.$idx ] ) ? $_POST[$option_name. '_attivatore'.$idx ] : ($activationRow['attivatore'] ?? ''),
                'regola' => isset( $_POST[ $option_name.'_regola'.$idx ] ) ? $_POST[ $option_name.'_regola'.$idx ] : ($activationRow['regola'] ?? ''),
                'ga_label' => isset( $_POST[ $option_name.'_ga_label'.$idx ] ) ? $_POST[ $option_name.'_ga_label'.$idx ] : ($activationRow['ga_label'] ?? ''),
                'ga_val' => isset( $_POST[ $option_name.'_ga_val'.$idx ] ) ? $_POST[$option_name. '_ga_val'.$idx ] : ($activationRow['ga_val'] ?? ''),
                'trk_val' => isset( $_POST[ $option_name.'_trk_val'.$idx ] ) ? $_POST[ $option_name.'_trk_val'.$idx ] : ($activationRow['trk_val'] ?? ''),
                'trk_label' => isset( $_POST[ $option_name.'_trk_label'.$idx ] ) ? $_POST[$option_name. '_trk_label'.$idx ] : ($activationRow['trk_label'] ?? ''),
            ];
        
        }, $activationRows, array_keys($activationRows) ) : [];

        //DELETE
        $id_to_delete = $_POST[$option_name."_hidden_for_delete"];
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