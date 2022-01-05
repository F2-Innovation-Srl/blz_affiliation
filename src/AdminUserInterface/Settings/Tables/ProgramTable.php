<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ProgramTable extends Table {

 
	protected function getTableFields($rows) {
        $this->title = "Program links"; 

        for ($i=0; $i<count($rows); $i++){
            $this->rows[] =  [
                "Subject Slug" => (new Fields\Text($this->option_name."subject_slug".$i,$rows[$i]["subject_slug"],"text")),
                "Subject Name" => (new Fields\Text($this->option_name."subject_name".$i,$rows[$i]["subject_name"],"text")),
                "Program Slug" => (new Fields\Text($this->option_name."program_slug".$i,$rows[$i]["program_slug"],"text")),
                "Program Name" => (new Fields\Text($this->option_name."program_name".$i,$rows[$i]["program_name"],"text")),
                "Update" => new Fields\Text($i,"Update","button"),
                "Delete" => new Fields\Text($i,"Delete","button",["hidden_field" => $this->option_name])
            ];
        }
        // FOR NEW INSERT
        $this->rows[] =  [
            "Subject Slug" => (new Fields\Text($this->option_name."subject_slug_new","","text")),
            "Subject Name" => (new Fields\Text($this->option_name."subject_name_new","","text")),
            "Program Slug" => (new Fields\Text($this->option_name."program_slug_new","","text")),
            "Program Name" => (new Fields\Text($this->option_name."program_name_new","","text")),
            "Aggiungi" => (new Fields\Text($this->option_name."_new",'Aggiungi',"button")),
            "Hidden" => (new Fields\Text($this->option_name."_hidden_for_delete",'',"hidden"))
        ];
        
    }


    protected function getAndSetRows(){
        
         // GET
         $rows = get_option( $this->option_name );

         // UPDATE
         $rows = ($rows) ? array_map( function ( $row, $idx  ) {

             return [
                'id' => $idx,
                'subject_slug' => isset( $_POST[$this->option_name. 'subject_slug'.$idx ] ) ? $_POST[$this->option_name. 'subject_slug'.$idx ] : ($row['subject_slug'] ?? ''),
                'subject_name' => isset( $_POST[ $this->option_name.'subject_name'.$idx ] ) ? $_POST[ $this->option_name.'subject_name'.$idx ] : ($row['subject_name'] ?? ''),
                'program_slug' => isset( $_POST[ $this->option_name.'program_slug'.$idx ] ) ? $_POST[ $this->option_name.'program_slug'.$idx ] : ($row['program_slug'] ?? ''),
                'program_name' => isset( $_POST[ $this->option_name.'program_name'.$idx ] ) ? $_POST[$this->option_name. 'program_name'.$idx ] : ($row['program_name'] ?? '')
            ];
        }, $rows, array_keys($rows) ) : [];

         //DELETE
        $id_to_delete = $_POST[$this->option_name."_hidden_for_delete"];
        if ($id_to_delete != "" && $id_to_delete != null){
            $rows = array_values(array_filter($rows,function($row) use($id_to_delete) {
                    return $row["id"] != $id_to_delete;
            }));  
        }

         //INSERT 
         if( !empty( $_POST[$this->option_name.'subject_slug_new'] ) ) {

            $rows[] = [
                'subject_slug' => $_POST[$this->option_name.'subject_slug_new'],
                'subject_name' => $_POST[$this->option_name.'subject_name_new'],
                'program_slug' => $_POST[$this->option_name.'program_slug_new'],
                'program_name' => $_POST[$this->option_name.'program_name_new']
            ];
        }
         // SET
         update_option( $this->option_name, $rows );
 
         //RETURN
         return $rows;

    }
}