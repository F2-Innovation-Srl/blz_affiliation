<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ProgramTable {

    protected $rows;
    protected $current;
    
	/**
	 * AttivazioneRow constructor.
	 */
	function __construct($option_name) {

        $rows = $this->getAndSetRows($option_name);

        for ($i=0; $i<count($rows); $i++){
            $this->rows[] =  [
                (new Fields\Text($option_name."programs_slug".$i,$rows[$i]["slug"],"text")),
                (new Fields\Text($option_name."programs_name".$i,$rows[$i]["name"],"text")),
                (new Fields\Text($i,"Update","button")),
                (new Fields\Text($i,"Delete","button"))
            ];
        }
        // FOR NEW INSERT
        $this->rows[] =  [
            (new Fields\Text($option_name."programs_slug_new","","text")),
            (new Fields\Text($option_name."programs_name_new","","text")),
            (new Fields\Text($option_name."_new",'Aggiungi',"button"))
            
        ];
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        ?>
        <div><h2>Program Links</h2></div>
            <table>
                <tr valign="top" style="text-align:left">
                    <th>Program slug</th><th>Program name</th>                    
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

            return [
                'id' => $idx,
                'slug' => isset( $_POST[$option_name. 'programs_slug'.$idx ] ) ? $_POST[$option_name. 'programs_slug'.$idx ] : $row['slug'],
                'name' => isset( $_POST[ $option_name.'programs_name'.$idx ] ) ? $_POST[ $option_name.'programs_name'.$idx ] : $row['name']
            ];
        
        }, $rows, array_keys($rows) ) : [];

        //DELETE
        $id_to_delete = $_POST['hidden_for_delete'];
        if ($id_to_delete != "" && $id_to_delete != null){
            $rows = array_values(array_filter($rows,function($row) use($id_to_delete) {
                    return $row["id"] != $id_to_delete;
            }));  
        }

        //INSERT 
        if( !empty( $_POST[$option_name.'programs_slug_new'] ) && !empty( $_POST[$option_name.'programs_name_new'] ) ) {

            $rows[] = [
                'slug' => $_POST[$option_name.'programs_slug'],
                'name' => $_POST[$option_name.'programs_name']
            ];
        }

        //SET
        update_option($option_name,$rows);

        //RETURN
        return $rows;

    }
}