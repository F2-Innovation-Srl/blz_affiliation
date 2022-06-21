<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Pages;

use BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\ProgramTableTaxonomies;
use BLZ_AFFILIATION\Taxonomies\AffiliateLinkProgram;
/**
 * È una pagina di settings provvisoria per fare dei test
 *
 * @package BLZ_AFFILIATION
 */
class ProgramLinksTaxonomies {
    private $is_valid_config;
    public $settings;
    protected $title;
    protected $option_name;
    protected $programs;
    protected $subjects;
    protected $current_tab;
   
    private $output = [
    "table" => 
        <<<HTML
        <div><h1>{{ title }}</h1></div>
        {{ tabs }}
        <form method="post" action="{{ link }}">
            {{ ProgramTable }}
            {{ wp_nonce }}
        </form>
        HTML,
    "tabs" =>
        <<<HTML
            <div id="icon-themes" class="icon32"><br></div>
            <h2 class="nav-tab-wrapper">{{ tabs }}</h2>
        HTML  
    ];
	/**
	 * 
	 */
	function __construct($is_valid_config,$title, $slug, $settings) {
        $this->is_valid_config     = $is_valid_config;
        $this->title               = $title;
        $this->settings            = $settings; 
        $this->option_name         = $slug;
        $this->current_tab = (isset($_GET["tab"])) ? $_GET["tab"] : 'blz-affiliation-programs';
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
            
            
         
            foreach (AffiliateLinkProgram::$taxonomies as $taxonomy_slug => $taxonomy_name)
                      $programTables[$taxonomy_slug] = (new ProgramTableTaxonomies($taxonomy_slug,$taxonomy_name))->render(); 
            
          
             echo str_replace(
                [ 
                    '{{ title }}',
                    '{{ link }}',
                    '{{ tabs }}',
                    '{{ ProgramTable }}',
                    '{{ wp_nonce }}'
                ],
                [ 
                    $this->title,
                    esc_html( admin_url( 'admin.php?page='.$_GET["page"].'&tab='.$this->current_tab ) ),
                    $this->renderTabs(),
                    $programTables[$this->current_tab],
                    wp_nonce_field( 'program-links-options-save', 'blz-affiliation-custom-message' )
                ],
                $this->output["table"]
            );
        }
    }

    public function renderTabs(){
        $tabs = "";    

        foreach (AffiliateLinkProgram::$taxonomies as $taxonomy_slug => $taxonomy_name)
            $tabs .= "<a class='nav-tab".(( $taxonomy_slug == $this->current_tab ) ? " nav-tab-active" : "")."' href='?page=".$_GET["page"]."&tab=".$taxonomy_slug."'>".$taxonomy_name."</a>";
       
        return str_replace( ['{{ tabs }}'], [ $tabs],  $this->output["tabs"] );
    }

}