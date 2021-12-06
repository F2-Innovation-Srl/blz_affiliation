<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings;

/**
 * Class Page
 *
 * @package BLZ_AFFILIATION
 */
class Page {
	/**
	 * Page constructor.
	 */
	function __construct() {
        
        add_action('init', [ $this, 'custom_enqueue' ]);
       
	}

	function custom_enqueue() { 
        add_action( 'wp_enqueue_scripts', [ $this, 'mytheme_enqueue_typekit' ] );
        
    }
    function mytheme_enqueue_typekit() { 
         wp_add_inline_script( 'mytheme-typekit', 'var blz_affiliation_ga=\'GA_sssss\'' );
    }

    // inline script via wp_print_scripts
    function shapeSpace_print_scripts() { 
        
        ?>
        
        <script>
            var var1 = <?php echo json_encode('var1'); ?>;
            var var2 = <?php echo json_encode('var2'); ?>;
            var var3 = <?php echo json_encode('var3'); ?>;
        </script>
        
        <?php
        
    }

}