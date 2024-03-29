<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\AffiliateMarketing\Request;
use BLZ_AFFILIATION\Rendering\Settings\SettingsData;
use BLZ_AFFILIATION\Rendering\Settings\PostData;
use BLZ_AFFILIATION\Utils\Helper;
use BLZ_AFFILIATION\Utils\Shortener;
/**
 * 
 * Ritorna i dati della tabella di affiliazione nella pagina
 * 
 */
class AffiliateTable {

    protected $post_id;
    
    function __construct() {

        // Add the shortcode to print the links
        add_shortcode( 'affiliate_table', [ $this, 'print'] );
    }

    
    public function print ( $atts, $content, $tag ) {

        $this->table_id = $atts['id'];
        $this->caption = (isset($atts['caption'])) ? $atts['caption'] : "";
        
        $table = $this->getTable();

        return $this->render( $table );
    }



    /**
     * Return the table's data
     *
     * @return array the table's data
     */
    public function getTable() {

        if( !have_rows('affiliate_table_row', $this->table_id ) ) return [];

        $table= get_field( 'affiliate_table_row', $this->table_id );

        return array_map( function( $row, $id ) {

            $id++;

            $post = get_post( $this->table_id );

            $request = new Request( [ 
                "keyword" => Helper::slugify($post->post_title),
                "marketplace" => Helper::slugify( $row['title'] ),
                "position" => $id
            ] );

            $settings = new SettingsData("blz_table", $request );
            $postData = PostData::getInstance();    
            return (object) [
                "id"               => $id,
                "marketplace"      => $row['title'],                
                "img"              => $row['image'],
                "text"             => $row['text'],
                "rating"           => $row['rating'],
                "cta"              => $row['cta'],
                "link"             => ($postData->is_amp  == "false") ?  $row['link'] : $row['link_amp'],
                "ga_event"         => $settings->getGAEvent()
            ];

        }, $table, array_keys($table)  );

    }


    protected function render( $table ) {

        $captionTemplate = <<<HTML
        <p class="blz_affiliation_table_caption">{{ caption }}</p>
        HTML;

        /// to enqueue CSS - table-rating.css        
        $tableTemplate = <<<HTML
            {{ caption }}
            <div class="rating-table">
                <ul class="rating-card blz_grid">
                    {{ header }}
                    {{ rows }}
                </ul>
            </div>
        HTML;

        $header = <<<HTML
                <li class="table_heading">
                    <div class="blz_col blz_col-1 blz_col-sm-12 blz_col-middle">
                        <div class="rating_heading">Rank</div>
                    </div>
                    <div class="blz_col blz_col-3 blz_col-sm-12 blz_col-middle">
                        <div class="rating_heading">Prodotto</div>
                    </div>
                    <div class="blz_col blz_col-3 blz_col-sm-12 blz_col-middle">
                        <div class="rating_heading">Caratteristiche</div> 
                    </div>
                    <div class="blz_col blz_col-3 blz_col-sm-12 blz_col-middle">
                        <div class="rating_heading" style="margin-left: -12px;">Rating</div>
                    </div>
                    <div class="blz_col blz_col-2 blz_col-sm-12 blz_col-middle">
                        <div class="rating_heading" style="margin-left: -24px;">Offerta</div>
                    </div>
                </li>
        HTML;
       
        $rowTemplate = <<<HTML
                <li data-vars-blz-affiliate="{{ ga_event }}">
                    <a href="{{ link }}" target="_blank" class="aftable_link">
                        <div class="blz_col blz_col-1 blz_col-sm-12 blz_col-middle">
                            <div class="rating_index">{{ id }}</div>
                        </div>
                        <div class="blz_col blz_col-3 blz_col-sm-12 blz_col-middle">
                            <div class="rating_image"><img src="{{ img }}" class="img-cover" alt="{{ marketplace }}"></div>
                        </div>                
                        <div class="blz_col blz_col-3 blz_col-sm-12 blz_col-middle">
                            <div class="rating_description">{{ text }}</div>
                        </div>                
                        <div class="blz_col blz_col-3 blz_col-sm-12 blz_col-middle">
                            <div class="rating_star"><div class="stars" style="--rating: {{ rating }};"></div></div>
                        </div>
                        <div class="blz_col blz_col-2 blz_col-sm-12 blz_col-middle">
                            <div class="rating_cta"><span class="btn btn-primary">{{ cta }}</span></div>
                        </div>
                    </a>
                </li>
        HTML;        
        
        $rows = array_reduce( $table, function( $markup, $row ) use ( $rowTemplate ) {
            $link = ( new Shortener )->generateShortLink( $row->link ) ;
            $markup .= str_replace (
                ['{{ ga_event }}', '{{ marketplace }}', '{{ img }}', '{{ link }}', '{{ id }}', '{{ text }}', '{{ rating }}', '{{ cta }}'],
                [ $row->ga_event, $row->marketplace, $row->img, $link, $row->id, $row->text, $row->rating, $row->cta ],
                $rowTemplate
            );

            return $markup;
        
        }, '');
        $caption = (!empty($this->caption)) ? str_replace('{{ caption }}',$this->caption,$captionTemplate) : "";
        
        Helper::setAffiliationPage();

        return str_replace(['{{ caption }}','{{ header }}','{{ rows }}' ], [ $caption, $header, $rows ], $tableTemplate );

    }


}
