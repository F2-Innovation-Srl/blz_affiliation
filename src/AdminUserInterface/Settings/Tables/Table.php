<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
abstract class Table {

    protected $option_name;
    protected $current;
    protected $title;
    protected $rows;
    protected $output = [
        "table" => 
            <<<HTML
            <div><h2>{{ title }}</h2></div>
            <table>
               <thead><tr valign="top" style="text-align:left">{{ headings }}</tr></thead>
                <tbody>{{ rows }}</thead>
            </table>
            HTML
    ];
    
	/**
	 * AttivazioneRow constructor.
	 */
	public function __construct($option_name, $current = null, $title = '') {

        $this->option_name = $option_name;
        $this->current = $current;
        $this->title = $title;
        $this->getTableFields($this->getAndSetRows());

    }

    abstract protected function getTableFields($rows);

    abstract protected function getAndSetRows();

	/**
     * stampa la tabella
     */
    public function render(){

        $rows = "";

        $fields = array_keys( $this->rows[0] );

        $headings = array_reduce( $fields, function( $headings, $field ) { 

            return $headings .= "<th>". $this->removeHiddenLabel( $field ) ."</th>";
        }, '' );

        $rows = array_reduce( $this->rows , function( $table_rows, $row ) {

            return $table_rows .= '<tr valign="top">'.array_reduce( $row, function( $cols, $field ) { 

                return $cols .= '<td >' . $field->render() . '</td>';;
            } ).'</tr>';

        }, '' );
        
        return str_replace([ '{{ title }}', '{{ headings }}', '{{ rows }}' ], [ $this->title, $headings, $rows ], $this->output["table"] );
    }

    protected function down( $a, $x ) {
        if( count($a)-1 > $x ) {
            $b = array_slice($a,0,$x,true);
            $b[] = $a[$x+1];
            $b[] = $a[$x];
            $b += array_slice($a,$x+2,count($a),true);
            return($b);
        } else { return $a; }
    }
    
    protected function up($a,$x) {
        if( $x > 0 and $x < count($a) ) {
            $b = array_slice($a,0,($x-1),true);
            $b[] = $a[$x];
            $b[] = $a[$x-1];
            $b += array_slice($a,($x+1),count($a),true);
            return($b);
        } else { return $a; }
    }

    protected function removeHiddenLabel($label){
        $return = "&nbsp;";
        $hiddenGA = (empty($this->current["marketplace"]["ga_event_template"]) ) ? "hidden" : "text"; 
        $hiddenTrack = (empty($this->current["marketplace"]["tracking_id"]) ) ? "hidden" : "text"; 
     
        if (! (str_contains(strtolower($label), 'hidden') 
            || str_contains(strtolower($label), 'update') 
            || str_contains(strtolower($label), 'azioni') 
            || str_contains(strtolower($label), 'delete')
            || ($hiddenGA && str_contains(strtolower($label), 'Valore GA'))
            || ($hiddenTrack && str_contains(strtolower($label), 'Valore TRK_ID')))
        ){
            $return = $label;
        }

        //eccezioni per gestione disclaimer
        if( isset($this->current["marketplace"]["ga_event_template"] ) && $this->current["marketplace"]["ga_event_template"] == "{disclaimer}" && $label == "Valore GA")     $return = "disclaimer";
        if( isset($this->current["marketplace"]["ga_event_template"] ) && $this->current["marketplace"]["ga_event_template"] == "{disclaimer}" && $label == "Valore TRK_ID") $return = "&nbsp;";
        	
        return $return;
    }
}