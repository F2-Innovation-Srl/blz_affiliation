<?php
namespace BLZ_AFFILIATION\AdminUserInterface\Settings\Modules\Fields;

/**
 * Una Row è un field di un modulo e ne gestisce tutte le sue caratteristiche
 */
class Rule extends Field {

    /// viene richiamata dal costruttore
    public function Create() {
        switch ($this->type) {
            case "":
                return '<input type="text" readonly name="'.$this->name.'" value="'.$this->value.'" />';
                break;
            case "POSTTYPE":
                return $this->printPostTypes();
                break;
            case "TAXONOMY":
                return $this->printTaxonomy();
                break;
            case "TAG":
                return $this->printTag();
                break;
            case "USERS":
                return $this->printUsers();
                break;
        } 
    }

    private function printPostTypes(){    
       
        $output = "<select name=\"".$this->name."\"><option value=\"0\">Seleziona un postype</option>";
            $posttypes = get_post_types(['public' => 'true']);
            
            foreach( $posttypes as $posttype) 
                $output .= "<option value=\"".$posttype."\" ".(($this->value == $posttype) ? "selected" : "")." >".$posttype."</option>";
                
        $output .= "</select>";
        return $output;
    }

    private function printTaxonomy(){    
       
        $row = get_option("blz-affiliation-settings");
        $tax = (isset($row["taxonomy"])) ? $row["taxonomy"] : "category";
        $output = "<select name=\"".$this->name."\"><option value=\"0\">Seleziona un termine</option>";
        $terms = get_terms( $tax, ['hide_empty' => true] );
            foreach( $terms as $term) 
                $output .= "<option value=\"".$term->term_id."\" ".(($this->value == $term->term_id) ? "selected" : "")." >".$term->name."</option>";
        $output .= "</select>";
        return $output;
    }

    private function printTag(){    

        $output = "<select name=\"".$this->name."\"><option value=\"0\">Seleziona un termine</option>";
        $terms = get_terms( 'post_tag', ['hide_empty' => true] );
            foreach( $terms as $term) 
                $output .= "<option value=\"".$term->term_id."\" ".(($this->value == $term->term_id) ? "selected" : "")." >".$term->name."</option>";
        $output .= "</select>";
        return $output;
    }

    private function printUsers(){    
       
        $output = "<select name=\"".$this->name."\"><option value=\"0\">Seleziona un utente</option>";
            $blogusers = get_users(['role__in' => ['author', 'subscriber']]);
            foreach( $blogusers as $user) 
                $output .= "<option value=\"".$user->ID."\" ".(($this->value == $user->ID) ? "selected" : "")." >".$user->display_name."</option>";
        $output .= "</select>";
        return $output;
    }
}