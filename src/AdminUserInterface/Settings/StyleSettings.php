<?php

namespace BLZ_AFFILIATION\AdminUserInterface\Settings;


/**
 * Imposta valori generali per gli stili
 
 * 
 */
class StyleSettings {


    private $options = [ 'primary' ];

    private $values;

    private $formOpen = <<<HTML
        <form method="post" action="options.php">

            <input type="text" name="{{ primary }}" value="OK" />    
    HTML;

    private $formClose = '</form>';

	function __construct( $option = 'blz-affiliation-style' ) {
        
        // add_action( 'admin_init', [ $this, 'registerStyleSettings' ] );

        $this->option = $option;
        
        $this->values = $this->getOptions();
    }

    private function getOptions(){
        
        return array_reduce( $this->options, function( $values , $option ){

            $values [ $option ] = get_option( $this->options . '-' . $option );
            return $values;
            
        }, [] ); 
    }

    function registerStyleSettings() {
       
        foreach( $this->options as $option ) {

            register_setting( $this->option.'-group', $this->option . '-' . $option );
        };
    }
    
    public function render() {

        echo str_replace( ['{{ primary }}'],[ $this->options['primary'] ], $this->formOpen );
        var_dump( $this->values ) ;

        submit_button();
        echo $this->formClose;
    }
    
    /*
     
    $primary = (!isset($primary) ? $primary : 'inherit';


a.affiliation-intext {
	font-family: inherit !important;
	font-size: inherit !important;
	color: inherit !important;
	text-decoration: underline !important;
	text-decoration-color: $primary !important;
}

a.affiliation-intext:hover {
	color: $primary !important;
}

     */
}