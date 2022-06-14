<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Tables\Fields;

/**
 * Campo della tabella di tipo "taxonomy"
 */
class Taxonomy extends Field {

    /**
     * HTML markup del campo
     *
     * @return string
     */
    public function Create() {

        // di default torna i nomi delle tassonomie
        // sarebbe meglio prendere gli slug
        $taxonomies = get_terms( ['taxonomy' => $this->type, 'parent' => 0 ,'hide_empty' => false] );

        $selected_taxonomies = $this->value ?: 0;

        $select = '<select name="'.$this->name.'">{{ options }}</select>';
        $options = '<option value="0">Nessun genitore</option>';
        $options .= array_reduce( $taxonomies, function( $markup, $taxonomy ) use  ($selected_taxonomies ) {
            
            $selected = ( $taxonomy->term_id == $selected_taxonomies ) ? ' selected ' : '';

            $markup .= '<option value="' . $taxonomy->term_id . '"' . $selected .'>' . $taxonomy->name . '</option>';
            return $markup;
        }, '');
    
        return str_replace('{{ options }}', $options, $select );        
    }

}