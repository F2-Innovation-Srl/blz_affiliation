<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ProgramTable extends Table {

 
	protected function getTableFields($row) {

        for ($i=0; $i<count($rows); $i++){
            $this->rows[] =  [
                "Slug" => (new Fields\Text($this->option_name."slug".$i,$rows[$i]["slug"],"text")),
                "Name" => (new Fields\Text($this->option_name."name".$i,$rows[$i]["name"],"text")),
                "Update" => (new Fields\Text($rows[$i]["term_id"],"Update","button")),
                "Delete" => (new Fields\Text($rows[$i]["term_id"],"Delete","button",["hidden_field" => $this->option_name])),
                "Hidden" => (new Fields\Text($this->option_name."term_id".$i,$rows[$i]["term_id"],"hidden")),
            ];
        }
        // FOR NEW INSERT
        $this->rows[] =  [
            "Slug" => (new Fields\Text($this->option_name."slug_new","","text")),
            "Name" => (new Fields\Text($this->option_name."name_new","","text")),
            "Aggiungi" => (new Fields\Text($this->option_name."_new",'Aggiungi',"button")),
            "Hidden" => (new Fields\Text($this->option_name."_hidden_for_delete",'',"hidden"))
        ];
        
    }


    protected function getAndSetRows(){
        
        //DELETE
        //echo "<pre>";
        $id_to_delete = $_POST[$this->option_name."_hidden_for_delete"];
        if ($id_to_delete != "" && $id_to_delete != null)  wp_delete_term( $id_to_delete,$this->option_name );

        //INSERT 
        if( !empty( $_POST[$this->option_name.'slug_new'] ) && !empty( $_POST[$this->option_name.'name_new'] ) ) 
            $term = wp_insert_term($_POST[$this->option_name.'name_new'],$this->option_name, ['slug' => $_POST[$this->option_name.'slug_new']]);
    
        //GET
        //$rows = get_option($this->option_name);
        $rows = get_terms($this->option_name, ['hide_empty' => false] );
        //UPDATE
        $rows = ($rows) ? array_map( function ( $row, $idx  )  {

            $slug = isset( $_POST[$this->option_name. 'slug'.$idx ] ) ? $_POST[$this->option_name. 'slug'.$idx ] : $row->slug;
            $name = isset( $_POST[ $this->option_name.'name'.$idx ] ) ? $_POST[ $this->option_name.'name'.$idx ] : $row->name;
            $term_id = isset( $_POST[ $this->option_name.'term_id'.$idx ] ) ? $_POST[ $this->option_name.'term_id'.$idx ] : $row->term_id;
            $term = wp_update_term($term_id,$this->option_name, ['name' => $name,'slug' => $slug]); 
            return [
                'term_id' => $term_id,
                'slug' =>  $slug,
                'name' => $name
            ];
        
        }, $rows, array_keys($rows) ) : [];
        //echo "</pre>";
        //RETURN
        return $rows;

    }
}