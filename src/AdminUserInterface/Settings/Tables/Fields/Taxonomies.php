<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;

/**
 * Campo della tabella di tipo "taxonomy"
 */
class Taxonomies extends Field {

    /**
     * HTML markup del campo
     *
     * @return string
     */
    public function Create() {

        // di default torna i nomi delle tassonomie
        // sarebbe meglio prendere gli slug
        $taxonomies = array_keys( get_taxonomies() );
       
        $selected_taxonomies = (unserialize( $this->value )) ?: [];

        $select = '<select size="8" multiple name="'.$this->name.'[]">{{ options }}</select>';
        
        $options = array_reduce( $taxonomies, function( $markup, $taxonomy ) use  ($selected_taxonomies ) {
            
            $selected = in_array( $taxonomy, $selected_taxonomies ) ? ' selected ' : '';

            $markup .= '<option value="' . $taxonomy . '"' . $selected .'>' . $taxonomy . '</option>';
            return $markup;
        }, '');
    
        return str_replace('{{ options }}', $options, $select );        
    }

}