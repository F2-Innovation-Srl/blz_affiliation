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
	public function __construct($option_name,$name) {

        $this->$option_name = $option_name;
        $this->getTableFields($this->getAndSetRows());

    }

    abstract protected function getTableFields();

    abstract protected function getAndSetRows();

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
        
        return str_replace([ '{{ title }}', '{{ headings }}', '{{ rows }}' ], [ $this->title, $headings, $rows ], $this->output["table"] );       

    }

}