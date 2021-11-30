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
                (new Fields\Text($option_name."id".$i,$rows[$i]["id"],"hidden")),
                (new Fields\Text($rows[$i]["id"],"Update","button")),
                (new Fields\Text($rows[$i]["id"],"Delete","button"))
            ];
        }
        // FOR NEW INSERT
        $this->rows[] =  [
            (new Fields\Text($option_name."slug_new","","text")),
            (new Fields\Text($option_name."name_new","","text")),
            (new Fields\Text($option_name."_new",'Aggiungi',"button"))
            
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
        
        //GET
        $rows = get_option($option_name);

        //UPDATE
        $rows = ($rows) ? array_map( function ( $row, $idx  )  use ($option_name)  {

            $slug = isset( $_POST[$option_name. 'slug'.$idx ] ) ? $_POST[$option_name. 'slug'.$idx ] : $row['slug'];
            $name = isset( $_POST[ $option_name.'name'.$idx ] ) ? $_POST[ $option_name.'name'.$idx ] : $row['name'];
            $id = isset( $_POST[ $option_name.'id'.$idx ] ) ? $_POST[ $option_name.'id'.$idx ] : $row['id'];
            $term = wp_update_term($id,$option_name, ['name' => $name,'slug' => $slug]); 
            return [
                'id' => $id,
                'slug' =>  $slug,
                'name' => $name
            ];
        
        }, $rows, array_keys($rows) ) : [];

        //DELETE
        $id_to_delete = $_POST['hidden_for_delete'];
        if ($id_to_delete != "" && $id_to_delete != null){
            $rows = array_values(array_filter($rows,function($row) use($id_to_delete) {
                    wp_delete_term( $row["id"],$option_name );
                    return $row["id"] != $id_to_delete;
            }));  
        }

        //INSERT 
        if( !empty( $_POST[$option_name.'slug_new'] ) && !empty( $_POST[$option_name.'name_new'] ) ) {
            $term = wp_insert_term($_POST[$option_name.'name_new'],$option_name, ['slug' => $_POST[$option_name.'slug_new']]);
            if (! isset($term["error"]))
                $rows[] = [
                    'id' => $term["term_id"],
                    'slug' => $_POST[$option_name.'slug_new'],
                    'name' => $_POST[$option_name.'name_new']
                ];
        }

        //SET
        update_option($option_name,$rows);

        //RETURN
        return $rows;

    }
}