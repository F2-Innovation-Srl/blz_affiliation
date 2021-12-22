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
    private $title = "Tabella di attivazione";
    private $output = [
        "table" => 
            <<<HTML
            <div><h2 id="tabella" name="tabella">{{ title }}</h2></div>
            <table>
               <thead><tr valign="top" style="text-align:left">{{ headings }}</tr></thead>
                <tbody>{{ rows }}</thead>
            </table>
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
                "hidden_up" => new Fields\Arrow($i,"","UP",["hidden_field" => $option_name]),
                "hidden_down" => new Fields\Arrow($i,"","DOWN",["hidden_field" => $option_name]),
                "Attivatore" => new Fields\Activator($option_name."_attivatore".$i,$rows[$i]["attivatore"]),
                "Regola" => new Fields\Rule($option_name."_regola".$i,$rows[$i]["regola"],$rows[$i]["attivatore"]),
                "Label" => new Fields\Label($option_name."_ga_label".$i,$rows[$i]["ga_label"],"GA",$current),
                "Valore GA" => new Fields\Text($option_name."_ga_val".$i,$rows[$i]["ga_val"],$hiddenGA),
                "Valore TRK_ID" => new Fields\Text($option_name."_trk_val".$i,$rows[$i]["trk_val"],$hiddenTrack),
                "Update" => new Fields\Text($i,"Update","button"),
                "Delete" => new Fields\Text($i,"Delete","button",["hidden_field" => $option_name])
            ];
        }
        // FOR NEW INSERT
        $this->rows[] =  [
            "hidden_up" => new Fields\Text($option_name."_hidden_for_up","","hidden"),
            "hidden_down" => new Fields\Text($option_name."_hidden_for_down","","hidden"),
            "Attivatore" => new Fields\Activator($option_name."_attivatore_new",""),
            "Regola" => new Fields\Rule($option_name."_regola_new",""),
            "Label" => new Fields\Label($option_name."_ga_label_new","","GA",$current),
            "Valore GA" => new Fields\Text($option_name."_ga_val_new","",$hiddenGA),
            "Valore TRK_ID" => new Fields\Text($option_name."_trk_val_new","",$hiddenTrack),
            "New" => new Fields\Text($option_name."_new",'Aggiungi',"button"),
            "hidden" => new Fields\Text($option_name."_hidden_for_delete",'',"hidden")
        ];
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        $headings = array_reduce( array_keys( $this->rows[0] ), function( $cols, $key ) { 

            $cols .= "<th>$key</th>";
            return $cols;
        } );

        foreach ($this->rows as $row)
        $rows.= '<tr valign="top">'.array_reduce( $row, function( $cols, $field ) { 

            $cols .= '<td >' . $field->render() . '</td>';
            return $cols;
        } ).'</tr>';
        
        return str_replace([ '{{ title }}', '{{ headings }}', '{{ rows }}' ], [$this->title, $headings, $rows ], $this->output["table"] );       
       
    }

    private function getAndSetRows($option_name){
        
        //GET
        $rows = get_option($option_name);

        //UPDATE
        $rows = ($rows) ? array_map( function ( $rows, $idx  )  use ($option_name)  {

            return [
                'id' => $idx,
                'attivatore' => isset( $_POST[$option_name. '_attivatore'.$idx ] ) ? $_POST[$option_name. '_attivatore'.$idx ] : ($row['attivatore'] ?? ''),
                'regola' => isset( $_POST[ $option_name.'_regola'.$idx ] ) ? $_POST[ $option_name.'_regola'.$idx ] : ($row['regola'] ?? ''),
                'ga_label' => isset( $_POST[ $option_name.'_ga_label'.$idx ] ) ? $_POST[ $option_name.'_ga_label'.$idx ] : ($row['ga_label'] ?? ''),
                'ga_val' => isset( $_POST[ $option_name.'_ga_val'.$idx ] ) ? $_POST[$option_name. '_ga_val'.$idx ] : ($row['ga_val'] ?? ''),
                'trk_val' => isset( $_POST[ $option_name.'_trk_val'.$idx ] ) ? $_POST[ $option_name.'_trk_val'.$idx ] : ($row['trk_val'] ?? ''),
            ];
        
        }, $rows, array_keys($rows) ) : [];

        //DELETE
        $id_to_delete = $_POST[$option_name."_hidden_for_delete"];
        if ($id_to_delete != "" && $id_to_delete != null){
            $rows = array_values(array_filter($rows,function($row) use($id_to_delete) {
                    return $row["id"] != $id_to_delete;
            }));  
        }

        //UP
        $id_to_up = $_POST[$option_name."_hidden_for_up"];
        if ($id_to_up != "" && $id_to_up != null)
            $rows = $this->up($rows,$id_to_up);
        
        //DOWN
        $id_to_down = $_POST[$option_name."_hidden_for_down"];
        if ($id_to_down != "" && $id_to_down != null)
            $rows = $this->down($rows,$id_to_down);


        //INSERT 
        if( !empty( $_POST[$option_name.'_attivatore_new'] ) ) {

            $rows[] = [
                'attivatore' => $_POST[$option_name.'_attivatore_new'],
                'regola' => $_POST[$option_name.'_regola_new'],
                'ga_label' => $_POST[$option_name.'_ga_label_new'],
                'ga_val' => $_POST[$option_name.'_ga_val_new'],
                'trk_val' => $_POST[$option_name.'_trk_val_new']
            ];
        }

        //SET
        update_option($option_name,$rows);

        //RETURN
        return $rows;

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