<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ActivationTable extends table{

    protected function getTableFields($rows) {

        $this->title = "Tabella di attivazione";

        $hiddenGA = (empty($this->current["marketplace"]["ga_event_template"]) ) ? "hidden" : "text"; 
        $hiddenTrack = (empty($this->current["marketplace"]["tracking_id"]) ) ? "hidden" : "text"; 
        
        for ($i=0; $i<count($rows); $i++){
            $this->rows[] =  [
                "hidden_up" => new Fields\Arrow($i,"","UP",["hidden_field" => $this->option_name]),
                "hidden_down" => new Fields\Arrow($i,"","DOWN",["hidden_field" => $this->option_name]),
                "Attivatore" => new Fields\Activator($this->option_name."_attivatore".$i,$rows[$i]["attivatore"]),
                "Regola" => new Fields\Rule($this->option_name."_regola".$i,$rows[$i]["regola"],$rows[$i]["attivatore"]),
                "Label" => new Fields\Label($this->option_name."_ga_label".$i,$rows[$i]["ga_label"],"GA",$this->current),
                "Valore GA" => new Fields\Text($this->option_name."_ga_val".$i,$rows[$i]["ga_val"],$hiddenGA),
                "Valore TRK_ID" => new Fields\Text($this->option_name."_trk_val".$i,$rows[$i]["trk_val"],$hiddenTrack),
                "Update" => new Fields\Text($i,"Update","button"),
                "Delete" => new Fields\Text($i,"Delete","button",["hidden_field" => $this->option_name])
            ];
        }
        // FOR NEW INSERT
        $this->rows[] =  [
            "hidden_up" => new Fields\Text($this->option_name."_hidden_for_up","","hidden"),
            "hidden_down" => new Fields\Text($this->option_name."_hidden_for_down","","hidden"),
            "Attivatore" => new Fields\Activator($this->option_name."_attivatore_new",""),
            "Regola" => new Fields\Rule($this->option_name."_regola_new",""),
            "Label" => new Fields\Label($this->option_name."_ga_label_new","","GA",$this->current),
            "Valore GA" => new Fields\Text($this->option_name."_ga_val_new","",$hiddenGA),
            "Valore TRK_ID" => new Fields\Text($this->option_name."_trk_val_new","",$hiddenTrack),
            "New" => new Fields\Text($this->option_name."_new",'Aggiungi',"button"),
            "hidden" => new Fields\Text($this->option_name."_hidden_for_delete",'',"hidden")
        ];
    }


    protected function getAndSetRows(){
        
        //GET
        $rows = get_option($this->option_name);
        //UPDATE
        if(empty($_POST[$this->option_name. "_activation_import"]) )
            $rows = ($rows) ? array_map( function ( $row, $idx  ) {
            return [
                'id' => $idx,
                'attivatore' => isset( $_POST[$this->option_name. '_attivatore'.$idx ] ) ? $_POST[$this->option_name. '_attivatore'.$idx ] : ($row['attivatore'] ?? ''),
                'regola' => isset( $_POST[ $this->option_name.'_regola'.$idx ] ) ? $_POST[ $this->option_name.'_regola'.$idx ] : ($row['regola'] ?? ''),
                'ga_label' => isset( $_POST[ $this->option_name.'_ga_label'.$idx ] ) ? $_POST[ $this->option_name.'_ga_label'.$idx ] : ($row['ga_label'] ?? ''),
                'ga_val' => isset( $_POST[ $this->option_name.'_ga_val'.$idx ] ) ? $_POST[$this->option_name. '_ga_val'.$idx ] : ($row['ga_val'] ?? ''),
                'trk_val' => isset( $_POST[ $this->option_name.'_trk_val'.$idx ] ) ? $_POST[ $this->option_name.'_trk_val'.$idx ] : ($row['trk_val'] ?? ''),
            ];
        
        }, $rows, array_keys($rows) ) : [];

        //DELETE
        $id_to_delete = isset($_POST[$this->option_name."_hidden_for_delete"]) ? $_POST[$this->option_name."_hidden_for_delete"] : null;
        if ($id_to_delete != "" && $id_to_delete != null){
            $rows = array_values(array_filter($rows,function($row) use($id_to_delete) {
                    return $row["id"] != $id_to_delete;
            }));  
        }

        //UP
        $id_to_up = isset($_POST[$this->option_name."_hidden_for_up"]) ? $_POST[$this->option_name."_hidden_for_up"] : null;
        if ($id_to_up != "" && $id_to_up != null)
            $rows = $this->up($rows,$id_to_up);
        
        //DOWN
        $id_to_down = isset($_POST[$this->option_name."_hidden_for_down"]) ? $_POST[$this->option_name."_hidden_for_down"] : null;
        if ($id_to_down != "" && $id_to_down != null)
            $rows = $this->down($rows,$id_to_down);


        //INSERT 
        if( !empty( $_POST[$this->option_name.'_attivatore_new'] ) ) {

            $rows[] = [
                'attivatore' => $_POST[$this->option_name.'_attivatore_new'],
                'regola' => $_POST[$this->option_name.'_regola_new'],
                'ga_label' => $_POST[$this->option_name.'_ga_label_new'],
                'ga_val' => $_POST[$this->option_name.'_ga_val_new'],
                'trk_val' => $_POST[$this->option_name.'_trk_val_new']
            ];
        }

        //print_r($_POST);
        //SET
        update_option($this->option_name,$rows);

        //RETURN
        return $rows;

    }

    
}