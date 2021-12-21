<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ActivationTable {

    private $rows;
    private $current;
    private $option_name;

    private $output = [
        "table" => 
            <<<HTML
            <div><h2 id="tabella" name="tabella">Tabella di attivazione</h2></div>
            {{ ActivationTableImport }}
            <table>
                <tr valign="top" style="text-align:left">{{ headings }}</tr>
                {{ trs }}  
            </table>
            HTML,
        "headings" => 
            <<<HTML
            <th>{{ th }}</th>
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
	function __construct($option_name,$current) {
        $this->current = $current;
        $this->option_name = $option_name;
        $rows = $this->getAndSetRows($option_name);
        $hiddenGA = (empty($this->current["marketplace"]["ga_event_template"]) ) ? "hidden" : "text"; 
        $hiddenTrack = (empty($this->current["marketplace"]["tracking_id"]) ) ? "hidden" : "text"; 

        for ($i=0; $i<count($rows); $i++){
            $this->rows[] =  [
                new Fields\Arrow($i,"","UP",["hidden_field" => $option_name]),
                new Fields\Arrow($i,"","DOWN",["hidden_field" => $option_name]),
                new Fields\Activator($option_name."_attivatore".$i,$rows[$i]["attivatore"]),
                new Fields\Rule($option_name."_regola".$i,$rows[$i]["regola"],$rows[$i]["attivatore"]),
                new Fields\Label($option_name."_ga_label".$i,$rows[$i]["ga_label"],"GA",$current),
                new Fields\Text($option_name."_ga_val".$i,$rows[$i]["ga_val"],$hiddenGA),
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
            new Fields\Text($option_name."_trk_val_new","",$hiddenTrack),
            new Fields\Text($option_name."_new",'Aggiungi',"button"),
            new Fields\Text($option_name."_hidden_for_delete",'',"hidden")
        ];
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        $labels = ["&nbsp;","&nbsp;","Attivatore","Regola","Label"];
        if (!empty($this->current["marketplace"]["ga_event_template"]))  $labels[] = "Valore GA";
        if (!empty($this->current["marketplace"]["tracking_id"])) $labels[] = "Valore TRK_ID";

        foreach( $labels as $label ) 
                    $headings[] = str_replace("{{ th }}",$label, $this->output["headings"]);

        foreach( $this->rows as $row ) {
            foreach( $row as $field ) 
                $tds[] = str_replace("{{ td }}",$field->render(), $this->output["tds"]);

            $trs[] = str_replace("{{ tds }}",implode("",$tds), $this->output["trs"]);
            $tds = [];
        }
                

        //print_r($tds);exit;
        return str_replace( 
            [
                '{{ ActivationTableImport }}',
                '{{ headings }}',
                '{{ trs }}'
            ], 
            [ 
                (new ActivationTableImport($this->option_name))->render(), 
                implode("",$headings),
                implode("",$trs),
            ],  
            $this->output["table"] 
        );

              
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
            ];
        
        }, $activationRows, array_keys($activationRows) ) : [];

        //DELETE
        $id_to_delete = $_POST[$option_name."_hidden_for_delete"];
        if ($id_to_delete != "" && $id_to_delete != null){
            $activationRows = array_values(array_filter($activationRows,function($row) use($id_to_delete) {
                    return $row["id"] != $id_to_delete;
            }));  
        }

        //UP
        $id_to_up = $_POST[$option_name."_hidden_for_up"];
        if ($id_to_up != "" && $id_to_up != null)
            $activationRows = $this->up($activationRows,$id_to_up);
        
        //DOWN
        $id_to_down = $_POST[$option_name."_hidden_for_down"];
        if ($id_to_down != "" && $id_to_down != null)
            $activationRows = $this->down($activationRows,$id_to_down);


        //INSERT 
        if( !empty( $_POST[$option_name.'_attivatore_new'] ) ) {

            $activationRows[] = [
                'attivatore' => $_POST[$option_name.'_attivatore_new'],
                'regola' => $_POST[$option_name.'_regola_new'],
                'ga_label' => $_POST[$option_name.'_ga_label_new'],
                'ga_val' => $_POST[$option_name.'_ga_val_new'],
                'trk_val' => $_POST[$option_name.'_trk_val_new']
            ];
        }

        //SET
        update_option($option_name,$activationRows);

        //RETURN
        return $activationRows;

    }

    function down($a,$x) {
        if( count($a)-1 > $x ) {
            $b = array_slice($a,0,$x,true);
            $b[] = $a[$x+1];
            $b[] = $a[$x];
            $b += array_slice($a,$x+2,count($a),true);
            return($b);
        } else { return $a; }
    }
    
    function up($a,$x) {
        if( $x > 0 and $x < count($a) ) {
            $b = array_slice($a,0,($x-1),true);
            $b[] = $a[$x];
            $b[] = $a[$x-1];
            $b += array_slice($a,($x+1),count($a),true);
            return($b);
        } else { return $a; }
    }
}