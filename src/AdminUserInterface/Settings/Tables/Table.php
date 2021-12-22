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
     * Print page if have correct permission
    **/
    public function render(){
        
        $headings = array_reduce( array_keys( $this->rows[0] ), function( $cols, $key ) { 

            $cols .= "<th>". $this->removeHiddenLabel($key) ."</th>";
            return $cols;
        } );

        foreach ($this->rows as $row)
        $rows.= '<tr valign="top">'.array_reduce( $row, function( $cols, $field ) { 

            $cols .= '<td >' . $field->render() . '</td>';
            return $cols;
        } ).'</tr>';
        
        return str_replace([ '{{ title }}', '{{ headings }}', '{{ rows }}' ], [ $this->title, $headings, $rows ], $this->output["table"] );       

    }

    protected function down($a,$x) {
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
        if (str_contains(strtolower($label), 'hidden') 
            || str_contains(strtolower($label), 'update') 
            || str_contains(strtolower($label), 'azioni') 
            || str_contains(strtolower($label), 'delete')
        ){
                return "&nbsp;";
        }else{
            return $label;
        }
    }
}