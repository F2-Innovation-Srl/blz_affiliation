<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;
 
/**
 * Class Row
 *
 * @package BLZ_AFFILIATION
 */
class ProgramSubjectTable {

    protected $rows;
    protected $current;
    
	/**
	 * AttivazioneRow constructor.
	 */
	function __construct($option_name) {

        $rows = $this->getAndSetRows($option_name);

        for ($i=0; $i<count($rows); $i++){
            $this->rows[] =  [
                (new Fields\Text($option_name."subjects".$i,$rows[$i]["slug"],"text")),
                (new Fields\Text($i,"Update","button")),
                (new Fields\Text($i,"Delete","button"))
            ];
        }
        // FOR NEW INSERT
        $this->rows[] =  [
            (new Fields\Text($option_name."subjects_new".$i,"","text")),
            (new Fields\Text($option_name."_new",'Aggiungi',"button")),
            (new Fields\Text("hidden_for_delete",'',"hidden")),
            
        ];
    }

	/**
     * Print page if have correct permission
    **/
    public function render(){
        ?>
        <div><h2>Subjects</h2></div>
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
        $activationRows = get_option($option_name);

        //UPDATE
        $activationRows = ($activationRows) ? array_map( function ( $activationRow, $idx  )  use ($option_name)  {

            return [
                'id' => $idx,
                'subjects' => isset( $_POST[$option_name. 'subjects'.$idx ] ) ? $_POST[$option_name. 'subjects'.$idx ] : $activationRow['slug']
            ];
        
        }, $activationRows, array_keys($activationRows) ) : [];

        //DELETE
        $id_to_delete = $_POST['hidden_for_delete'];
        if ($id_to_delete != "" && $id_to_delete != null){
            $activationRows = array_values(array_filter($activationRows,function($row) use($id_to_delete) {
                    return $row["id"] != $id_to_delete;
            }));  
        }

        //INSERT 
        if( !empty( $_POST[$option_name.'subjects'] ) ) {

            $activationRows[] = [
                'slug' => $_POST[$option_name.'subjects']
            ];
        }

        //SET
        update_option($option_name,$activationRows);

        //RETURN
        return $activationRows;

    }
}