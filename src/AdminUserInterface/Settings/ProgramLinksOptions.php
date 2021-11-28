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

            $action = esc_html( admin_url( 'admin.php?page='.$_GET["page"] ) );
            ?>
        
            <form method="post" action="<?=$action?>">
                
                
                <?php  (new ProgramTable("blz_programs"))->render(); ?>
                <div><hr></div>
                <?php  (new ProgramTable("blz_programs_subjects"))->render(); ?>
                
                <?php wp_nonce_field( 'program-links-options-save', 'blz-affiliation-custom-message' ); ?>
                
            </form>
            <!-- .wrap -->
            <?php 
        }
    }

}