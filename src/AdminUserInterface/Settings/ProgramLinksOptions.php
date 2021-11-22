<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

/**
 * È una pagina di settings provvisoria per fare dei test
 *
 * @package BLZ_AFFILIATION
 */
class ProgramLinksOptions {
    
    public $page = "blz-affiliation-program-links-options";
    
    protected $programs;
    protected $subjects;
    protected $default_tracking_id = 'tn-news';
    
	/**
	 * 
	 */
	function __construct( ) {
                
        // set admin actions callback
        add_action( 'admin_menu', [ $this, 'adminMenu' ] );
	}

	/**
     * Invoked on admin_menu action
     * Create admin menu 
    **/
	public function adminMenu() {

        add_menu_page('Program Links Options', 'Program Links Options', 'manage_options', $this->page, [ $this, 'render']);

	}

	/**
     * Print page if have correct permission
    **/
    public function render()
    {
        if ( !current_user_can('manage_options') ) {
            
            wp_die('Non hai i permessi per visualizzare questa pagina');

        } else {

            $this->printPage();
        }
    }

    /**
     * Print Page
     */
    private function printPage()
    {
        $this->form();


        /*
        ?>
        <form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page='.$this->page.'&tab='.$this->current_tab ) ); ?>">
            <input type="hidden" name="blz-affiliation-sendForm" value="OK" />
            <?php $this->printTabs(); ?>
            <div class="blz-affiliation-container">
                <h2><?php echo $this->marketplaces[$this->current_tab]->getPanelDescription();?></h2>
                <?php $formBuilder->printForm(); ?>
            </div>
            <div><hr></div>
            <?php 
                wp_nonce_field( 'blz-affiliation-settings-save', 'blz-affiliation-custom-message' );
                submit_button();
            ?>
        </form></div><!-- .wrap -->
    <?php */
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
        $action = esc_html( admin_url( 'admin.php?page='.$this->page ) );

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
            </table>
            
            <div><hr></div>
            
            <?php endif; ?>
            
            <h2>Add a program</h2>
            <table >
                <tr valign="top" style="text-align:left">
                    <th>Program slug</th><th>Program name</th>                    
                </tr>
                <tr valign="top">                    
                    <td><input type="text" name="programs_slug_new" value="" /></td>
                    <td><input type="text" name="programs_name_new" value="" /></td>
                    <td><?php submit_button('Add', 'primary', 'submit', false ); ?></td>                    
                </tr>
            </table>
            

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
            </table>
            
            <div><hr></div>
            <?php endif; ?>
            
            <h2>Add a subject</h2>
            <table >
                <tr valign="top" style="text-align:left">
                    <th>Subject</th>
                    <td><input type="text" name="subject_new" value="" /></td>
                    <td><?php submit_button('Add', 'primary', 'submit', false ); ?></td>     
                </tr>
                
            </table>
            


            <?php wp_nonce_field( 'program-links-options-save', 'blz-affiliation-custom-message' ); ?>
            
        </form>
        <!-- .wrap -->
        <?php 
    }

    
    
    
}