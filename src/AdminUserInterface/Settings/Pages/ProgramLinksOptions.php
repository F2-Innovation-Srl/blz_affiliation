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
    private $is_valid_config;
    public $settings;
    protected $title;
    protected $option_name;
    protected $programs;
    protected $subjects;
    protected $default_tracking_id = 'tn-news';

    private $output =
    <<<HTML
    <div><h1>{{ title }}</h1></div>
    <form method="post" action="{{ link }}">
        {{ ProgramTable }}
        {{ wp_nonce }}
    </form>
    HTML;
    
	/**
	 * 
	 */
	function __construct($is_valid_config,$title, $slug, $settings) {
        $this->is_valid_config     = $is_valid_config;
        $this->title               = $title;
        $this->settings            = $settings; 
        $this->option_name         = $slug;
    }

	/**
     * Print page if have correct permission
    **/
    public function render()
    {
        if (!$this->is_valid_config)  wp_die('Per utilizzare il plugin occorre prima caricare le configurazioni');
        if ( !current_user_can('manage_options') ) {
            wp_die('Non hai i permessi per visualizzare questa pagina');
        } else {
            /*
            $taxonomies = [ 
                'blz-affiliation-programs' => 'Programs',
                'blz-affiliation-subjects' => 'Subjects'
            ]; 

            foreach ($taxonomies as $taxonomy_slug => $taxonomy_name)
                      $programTables[] = (new ProgramTable($taxonomy_slug,null,$taxonomy_name))->render(); 
            */
             echo str_replace(
                [ 
                    '{{ title }}',
                    '{{ link }}',
                    '{{ ProgramTable }}',
                    '{{ wp_nonce }}'
                ],
                [ 
                    $this->title,
                    esc_html( admin_url( 'admin.php?page='.$_GET["page"] ) ),
                    (new ProgramTable($this->option_name))->render(),
                    wp_nonce_field( 'program-links-options-save', 'blz-affiliation-custom-message' )
                ],
                $this->output
            );
        }
    }

}