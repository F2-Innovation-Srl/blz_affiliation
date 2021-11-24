<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

/**
 * È una pagina di settings provvisoria per fare dei test
 *
 * @package BLZ_AFFILIATION
 */
class ProgramLinksOptions {
     
    protected $programs;
    protected $subjects;
    protected $default_tracking_id = 'tn-news';
    
	/**
	 * 
	 */
	function __construct( ) {}

	/**
     * Print page if have correct permission
    **/
    public function render()
    {
        if ( !current_user_can('manage_options') ) {
            
            wp_die('Non hai i permessi per visualizzare questa pagina');

        } else {

            $this->form();
        }
    }

 

    private function getPrograms(){

        $programs = get_option( 'blz_programs' );

        $programs = ($programs) ? array_map( function ( $program, $idx  )  {

            return [
                'slug' => isset( $_POST[ 'programs_slug'.$idx ] ) ? $_POST[ 'programs_slug'.$idx ] : $program['slug'],
                'name' => isset( $_POST[ 'programs_name'.$idx ] ) ? $_POST[ 'programs_name'.$idx ] : $program['name']
            ];

        }, $programs, array_keys($programs) ) : [];

        if( !empty( $_POST['programs_slug_new'] ) && !empty( $_POST['programs_name_new'] ) ) {

            $programs[] = [
                'slug' => $_POST['programs_slug_new'],
                'name' => $_POST['programs_name_new']
            ];
        }

        update_option('blz_programs', $programs );

        return $programs;

    }


    private function getSubjects(){

        $subjects = get_option( 'blz_programs_subjects' );

        $subjects = ($subjects) ? array_map( function ( $subject, $idx  )  {

            return isset( $_POST[ 'subjects'.$idx ] ) ? $_POST[ 'subjects'.$idx ] : $subject;                           

        }, $subjects, array_keys($subjects) ) : [];


        if( !empty( $_POST['subject_new'] ) ) {

            $subjects[] = $_POST['subject_new'];
        }

        update_option('blz_programs_subjects', $subjects );

        return $subjects;

    }

    
    private function form()
    { 
        $action = esc_html( admin_url( 'admin.php?page='.$_GET["page"] ) );

        $programs = $this->getPrograms();
        $subjects = $this->getSubjects();
        
        ?>
    
        <form method="post" action="<?=$action?>">
            
            <?php if(!empty($programs)):?>
        
            <div><h2>Program Links</h2></div>

            <table >
                <tr valign="top" style="text-align:left">
                    <th>Program slug</th><th>Program name</th>                    
                </tr>
                <?php foreach( $programs as $idx => $program ) : ?>

                    <tr valign="top">                    
                        <td><input type="text" name="programs_slug<?=$idx?>" value="<?=$program['slug']?>" /></td>
                        <td><input type="text" name="programs_name<?=$idx?>" value="<?=$program['name']?>" /></td>
                        <td><?php submit_button('Update', 'primary', 'submit', false ); ?></td> 
                    </tr>

                <?php endforeach; ?>
                <tr valign="top">                    
                    <td><input type="text" name="programs_slug_new" value="" /></td>
                    <td><input type="text" name="programs_name_new" value="" /></td>
                    <td><?php submit_button('Add', 'primary', 'submit', false ); ?></td>                    
                </tr>
            </table>
            
            <div><hr></div>
            
            <?php endif; ?>

            <div><hr></div>

            <?php if(!empty($subjects)):?>
            <div><h2>Subjects</h2></div>

            <table >
                <tr valign="top" style="text-align:left"><th>Subject</th></tr>
                
                <?php foreach( $subjects as $idx => $subject ) : ?>

                    <tr valign="top">                    
                        <td><input type="text" name="subjects<?=$idx?>" value="<?=$subject?>" /></td>                        
                        <td><?php submit_button('Update', 'primary', 'submit', false ); ?></td> 
                    </tr>

                <?php endforeach; ?>
                <tr valign="top" style="text-align:left">
                    <td><input type="text" name="subject_new" value="" /></td>
                    <td><?php submit_button('Add', 'primary', 'submit', false ); ?></td>     
                </tr>
            </table>
            
            <div><hr></div>
            <?php endif; ?>


            <?php wp_nonce_field( 'program-links-options-save', 'blz-affiliation-custom-message' ); ?>
            
        </form>
        <!-- .wrap -->
        <?php 
    }

    
    
    
}