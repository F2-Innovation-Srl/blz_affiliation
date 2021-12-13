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
            case "USERS":
                return $this->printUsers();
                break;
            default:
                return $this->printTaxonomy();
                break;
        } 
    }

    private function printPostTypes(){    
       
        $output = "<select name=\"".$this->name."\"><option value=\"0\">Seleziona un postype</option>";
        $output = $this->addCommonOptions($output);
        $posttypes = get_post_types(['public' => 'true']);
        foreach( $posttypes as $posttype) 
            $output .= "<option value=\"".$posttype."\" ".(($this->value == $posttype) ? "selected" : "")." >".$posttype."</option>";   
        $output .= "</select>";
        return $output;
    }

    private function printTaxonomy(){    
       
        $output = "<select name=\"".$this->name."\"><option value=\"0\">Seleziona un termine</option>";
        $output = $this->addCommonOptions($output);
        $terms = get_terms( $this->type, ['hide_empty' => true] );
            foreach( $terms as $term) 
                $output .= "<option value=\"".$term->slug."\" ".(($this->value == $term->slug) ? "selected" : "")." >".$term->name."</option>";
        $output .= "</select>";
        return $output;
    }

    private function printUsers(){    
       
        $output = "<select name=\"".$this->name."\"><option value=\"0\">Seleziona un utente</option>";
        $output = $this->addCommonOptions($output);
        $blogusers = get_users(); //['role__in' => ['author', 'subscriber']]
        foreach( $blogusers as $user) 
            $output .= "<option value=\"".$user->ID."\" ".(($this->value == $user->ID) ? "selected" : "")." >".$user->display_name."</option>";
        $output .= "</select>";
        return $output;
    }
    private function addCommonOptions($output){    
        $output.='<option value="custom_valore">Sovrascrivi tutti con</option>';
        $output.='<option value="blz_valore">Usa il suo valore</option>';
    }
}