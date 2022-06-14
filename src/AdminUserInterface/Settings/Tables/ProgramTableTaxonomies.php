<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ProgramTableTaxonomies {

    protected $name;
    protected $output = [
        "table" => 
            <<<HTML
             <div><h2>{{ headings }}</h2></div>
            <table>
                <tr valign="top" style="text-align:left">
                    <th>Slug</th><th>Name</th>                    
                    {{ rows }}
            </table>
            HTML
    ];
	/**
	 * AttivazioneRow constructor.
	 */
	function __construct($option_name,$name) {

        $rows = $this->getAndSetRows($option_name);
        $current_tab = (isset($_GET["tab"])) ? $_GET["tab"] : 'blz-affiliation-page-type';
        $this->name = $name;
        for ($i=0; $i<count($rows); $i++){
            $this->rows[] =  [
                (new Fields\Text($option_name."slug".$i,$rows[$i]["slug"],"text")),
                (new Fields\Text($option_name."name".$i,$rows[$i]["name"],"text")),
                (new Fields\Taxonomy($option_name."parent".$i,$rows[$i]["parent"],$current_tab)),
                (new Fields\Text($rows[$i]["term_id"],"Update","button")),
                (new Fields\Text($rows[$i]["term_id"],"Delete","button",["hidden_field" => $option_name])),
                (new Fields\Text($option_name."term_id".$i,$rows[$i]["term_id"],"hidden")),
            ];
        }
        // FOR NEW INSERT
        $this->rows[] =  [
            (new Fields\Text($option_name."slug_new","","text")),
            (new Fields\Text($option_name."name_new","","text")),
            (new Fields\Taxonomy($option_name."parent_new",0,$current_tab)),
            (new Fields\Text($option_name."_new",'Aggiungi',"button")),
            (new Fields\Text($option_name."_hidden_for_delete",'',"hidden"))
        ];
        
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){

        $rows = "";
        $headings = $this->name;

        foreach ($this->rows as $row)
        $rows.= '<tr valign="top">'.array_reduce( $row, function( $cols, $field ) { 

            $cols .= '<td >' . $field->render() . '</td>';
            return $cols;
        } ).'</tr>';
        
        return str_replace([ '{{ title }}', '{{ headings }}', '{{ rows }}' ], [ $this->title, $headings, $rows ], $this->output["table"] ); 
    }

    private function getAndSetRows($option_name){
        $newValue = false;
        //DELETE
        //echo "<pre>";
        $id_to_delete = $_POST[$option_name."_hidden_for_delete"];
        if ($id_to_delete != "" && $id_to_delete != null)  wp_delete_term( $id_to_delete,$option_name );
        //INSERT 
        if( !empty( $_POST[$option_name.'slug_new'] ) && !empty( $_POST[$option_name.'name_new'] ) ) {
            $newValue = true;
            $term = wp_insert_term($_POST[$option_name.'name_new'],$option_name, ['slug' => $_POST[$option_name.'slug_new'],'parent' => $_POST[$option_name.'parent_new']]);
        }
        //GET
        //$rows = get_option($option_name);
        $rows = get_terms($option_name, ['orderby' => 'parent', 'hide_empty' => false] );

        //UPDATE
        $rows = ($rows) ? array_map( function ( $row, $idx  )  use ($option_name,$newValue)  {
            $slug = (isset( $_POST[$option_name. 'slug'.$idx ]) && !$newValue ) ? $_POST[$option_name. 'slug'.$idx ] : $row->slug;
            $name = (isset( $_POST[ $option_name.'name'.$idx ]) && !$newValue ) ? $_POST[ $option_name.'name'.$idx ] : $row->name;
            $term_id = (isset( $_POST[ $option_name.'term_id'.$idx ]) && !$newValue ) ? $_POST[ $option_name.'term_id'.$idx ] : $row->term_id;
            $parent = (isset( $_POST[ $option_name.'parent'.$idx ]) && !$newValue ) ? $_POST[ $option_name.'parent'.$idx ] : $row->parent;
            if (!$newValue ) $term = wp_update_term($term_id,$option_name, ['name' => $name,'slug' => $slug,'parent' => $parent]); 
            return [
                'term_id' => $term_id,
                'slug' =>  $slug,
                'name' => $name,
                'parent' => $parent
            ];
        }, $rows, array_keys($rows) ) : [];
        //echo "</pre>";
        //RETURN
        return $rows;

    }
}