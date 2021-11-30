<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ProgramTable {

    protected $name;
    
	/**
	 * AttivazioneRow constructor.
	 */
	function __construct($option_name,$name) {

        $rows = $this->getAndSetRows($option_name);
        $this->name = $name;
        for ($i=0; $i<count($rows); $i++){
            $this->rows[] =  [
                (new Fields\Text($option_name."slug".$i,$rows[$i]["slug"],"text")),
                (new Fields\Text($option_name."name".$i,$rows[$i]["name"],"text")),
                (new Fields\Text($rows[$i]["term_id"],"Update","button")),
                (new Fields\Text($rows[$i]["term_id"],"Delete","button",["hidden_field" => $option_name])),
                (new Fields\Text($option_name."term_id".$i,$rows[$i]["term_id"],"hidden")),
            ];
        }
        // FOR NEW INSERT
        $this->rows[] =  [
            (new Fields\Text($option_name."slug_new","","text")),
            (new Fields\Text($option_name."name_new","","text")),
            (new Fields\Text($option_name."_new",'Aggiungi',"button")),
            (new Fields\Text($option_name."_hidden_for_delete",'',"hidden"))
        ];
        
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        ?>
        <div><h2><?php echo $this->name?></h2></div>
            <table>
                <tr valign="top" style="text-align:left">
                    <th>Slug</th><th>Name</th>                    
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
        
        //DELETE
        //echo "<pre>";
        $id_to_delete = $_POST[$option_name."_hidden_for_delete"];
        if ($id_to_delete != "" && $id_to_delete != null)  wp_delete_term( $id_to_delete,$option_name );

        //INSERT 
        if( !empty( $_POST[$option_name.'slug_new'] ) && !empty( $_POST[$option_name.'name_new'] ) ) 
            $term = wp_insert_term($_POST[$option_name.'name_new'],$option_name, ['slug' => $_POST[$option_name.'slug_new']]);
    
        //GET
        //$rows = get_option($option_name);
        $rows = get_terms($option_name, ['hide_empty' => false] );
        //UPDATE
        $rows = ($rows) ? array_map( function ( $row, $idx  )  use ($option_name)  {

            $slug = isset( $_POST[$option_name. 'slug'.$idx ] ) ? $_POST[$option_name. 'slug'.$idx ] : $row->slug;
            $name = isset( $_POST[ $option_name.'name'.$idx ] ) ? $_POST[ $option_name.'name'.$idx ] : $row->name;
            $term_id = isset( $_POST[ $option_name.'term_id'.$idx ] ) ? $_POST[ $option_name.'term_id'.$idx ] : $row->term_id;
            $term = wp_update_term($term_id,$option_name, ['name' => $name,'slug' => $slug]); 
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