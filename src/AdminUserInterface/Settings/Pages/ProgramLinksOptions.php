<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Pages;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\ProgramTable;
use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\ProgramSubjectTable;
/**
 * È una pagina di settings provvisoria per fare dei test
 *
 * @package BLZ_AFFILIATION
 */
class ProgramLinksOptions {
     
    protected $settings;
    protected $slug;
    protected $programs;
    protected $subjects;
    protected $default_tracking_id = 'tn-news';
    
	/**
	 * 
	 */
	function __construct($slug, $settings) {

        $this->settings  = $settings; 
        $this->slug = $slug;
    }

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
                <?php 
                $taxonomies = [ 
                    'blz-affiliation-programs'=> 'Programs',
                    'blz-affiliation-subjects' => 'Subjects'
                ];        
                foreach ($taxonomies as $taxonomy_slug => $taxonomy_name)
                        (new ProgramTable($taxonomy_slug,$taxonomy_name))->render(); 
                ?>
                <?php wp_nonce_field( 'program-links-options-save', 'blz-affiliation-custom-message' ); ?>
                
            </form>
            <!-- .wrap -->
            <?php 
        }
    }

}