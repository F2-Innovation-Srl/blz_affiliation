<?php

namespace BLZ_AFFILIATION\AdminUserInterface\Buttons;

/*
 * Class Button
 * 
 * È una classe astratta che incapsula tutto il meccanismo di registrazione 
 * delle funzioni necessarie alla creazione del plugin tinymcs
 * e lascia da scrivere solo l'endpoint ajax_axtion() che sarà richiamato
 * dal plugin javascript
 * 
 * per creare un bottone bisogna quindi
 * 
 * 1. estendere la classe Button
 * 
 * 2. impostare nel costruttore della nuova classe il nome del plugin e il nome dell'action (l'endpoint da richiamare)
 *    lo si fa semplicemente richiamando il costruttore della classe base e passando i nomi
 * 
 * 3. nella cartellina plugins creare un nuovo file js (o duplicare uno dei file presenti) e chiamarlo
 *    con lo stesso nome di plugin appen dichiarato       
 *    al suo interno 
 *    addButton()  --> impostare il nome e l'icona 
 *    addCommand() --> puntare la action ( l'endpoint che abbiamo appena creato )
 * 
 * 4. scrivere il codice dell'endpoint (che potrebbe richiamare un template html o php per creare il form nella dialog box)
 * 
 * 5. piazzare tutti i template della dialog nella cartella plugin (non è obbligatorio ma è meglio)
 * 
 * 6. istanziare il nuovo bottone nel functions.php
 *  
 */
abstract class Button {

    protected $base_dir;
    protected $base_url;

    protected $name;
    protected $action;

    function __construct($name, $action) { 
      
        $this->base_url = PLUGIN_URI .'/src/AdminUserInterface/Buttons/';
        $this->base_dir = PLUGIN_PATH .'/src/AdminUserInterface/Buttons/';

        $this->name   = $name;
        $this->action = $action;
         
        // the action needed to add the button
        add_action('admin_head', [ $this, 'add' ]);

        // create the endpoint called by the dialog box
        add_action("wp_ajax_$this->action", [ $this, 'ajax_action' ]);

    }

    function add() {
    
        // Check if user have permission
        if (!current_user_can('edit_posts') || !current_user_can('edit_pages')) return;
        
        // Check if WYSIWYG is enabled
        if ('true' == get_user_option('rich_editing')) {

            // add the plugin (add a row into the plugins list)
            add_filter('mce_external_plugins', [ $this , 'add_plugin']);
            // register the button into the first row of the toolbar
            add_filter('mce_buttons',          [ $this , 'register_button']);

        }
    }

    function add_plugin($plugin_array) {

        $plugin_array[ $this->name ] = $this->base_url . "plugins/$this->name.js";        
        return $plugin_array;
    }

    function register_button($buttons) {

        array_push( $buttons, $this->name );    
        return $buttons;
    }

    /**
     * This is the endpoint function to override
     * into the extended class
     */
    abstract public function ajax_action();
    
    /* exsample
    public function ajax_action() {

        // check for rights
        if (! current_user_can('publish_posts'))  { die( __("Vietato") ); } 


        // get the template HTML
        $html = file_get_contents( $this->base_dir .'plugins/dialog-<CLASSNAME>.html');        
        
        $fields_to_inject =  [ ];    elenco delle coppie chiave-valore da sostituire nel template
        
        // inject the variables into the html template
        foreach($fields_to_inject as $key => $value)
            $html = str_replace ( '{{'.$key.'}}' , $value , $html );

        // print the block
        header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
        echo $html;


        // oppure con PHP
        // include_once(get_template_directory() .'/src/UserInterface/Editor/Buttons/plugins/dialog-<CLASSNAME>.php' );

        die();
    }*/
    
}