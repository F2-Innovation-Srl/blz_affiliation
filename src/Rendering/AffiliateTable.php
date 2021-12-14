<?php

namespace BLZ_AFFILIATION\Rendering;

use BLZ_AFFILIATION\AffiliateMarketing\Request;
use BLZ_AFFILIATION\Utils\Helper;
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
        
        $table = $this->getTable();

        $this->render( $table );
    }



    /**
     * Return the table's data
     *
     * @return array the table's data
     */
    public function getTable() {

        if( !have_rows('affiliate_table_row', $this->table_id) ) return [];

        $table = [];
        $id=1;
        
        while ( have_rows('affiliate_table_row', $this->table_id) ) : the_row();
            $post = get_post($this->table_id);
            $table[]= [
                "table_id"               => $id++,
                "table_title"            => Helper::slugify($post->title),
                "table_marketplace"      => get_sub_field('title'),
                "table_marketplace_slug" => Helper::slugify( get_sub_field('title') ),
                "table_img"              => get_sub_field('image'),
                "table_text"             => get_sub_field('text'),
                "table_rating"           => get_sub_field('rating'),
                "table_cta_text"         => get_sub_field('cta'),
                "table_link"             => get_sub_field('link'),
                "table_link_amp"         => get_sub_field('link_amp'),
            ];

        endwhile;

        return $table;
    }


    protected function render( $table ) {

        /// bisogna fare l'equeing del CSS
        /// data-css-dependency="styles/components/partials/table-rating.css"
        ?>
        
        <div class="rating-table">
            <ul class="rating-card grid">
                <li class="table_heading">
                    <div class="col col-1 col-sm-12 col-middle">
                        <div class="rating_heading">Rank</div>
                    </div>
                    <div class="col col-3 col-sm-12 col-middle">
                        <div class="rating_heading">Prodotto</div>
                    </div>
                    <div class="col col-3 col-sm-12 col-middle">
                        <div class="rating_heading">Caratteristiche</div> 
                    </div>
                    <div class="col col-3 col-sm-12 col-middle">
                        <div class="rating_heading" style="margin-left: -12px;">Rating</div>
                    </div>
                    <div class="col col-2 col-sm-12 col-middle">
                        <div class="rating_heading" style="margin-left: -24px;">Offerta</div>
                    </div>
                </li>

                <?php 
                /// recupera i dati della pagina
                $postData = new PostData();
                foreach( $table as $item ): 
                    $SettingsData = new SettingsData($postData,"blz_table",(new Request(["marketplace" => $item->table_marketplace_slug, "position" => $item->table_id])));
                ?>

                    <li data-vars-affiliate="<?=$SettingsData->getGAEvent()?>">
                           <a href="<?=$item->table_link?>" target="_blank" class="aftable_link">

                            <div class="col col-1 col-sm-12 col-middle">
                                <div class="rating_index"><?=$item->table_id?></div>
                            </div>
                        
                            <div class="col col-3 col-sm-12 col-middle">
                                <div class="rating_image">
                                    <img src="<?=$item->table_img?>" class="img-cover" alt="<?=$item->table_marketplace?>">
                                </div>
                            </div>
                
                            <div class="col col-3 col-sm-12 col-middle">
                                <div class="rating_description"><?=$item->table_text?></div>
                            </div>
                
                            <div class="col col-3 col-sm-12 col-middle">
                                <div class="rating_star">
                                    <div class="stars" style="--rating: <?=$item->table_rating?>;"></div>
                                </div>
                            </div>
                
                            <div class="col col-2 col-sm-12 col-middle">
                                <div class="rating_cta">
                                    <span class="btn btn-primary"><?=$item->table_cta_text?></span>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <?php
    }
    
    /**
     * Crea il link a partire dai dati della pagina
     * e da quelli di un singolo link nel testo
     *
     * @param Link $linkData
     * @return string
     */
    private function FillTemplate( $link, $ga_event, $tracking, $template) {

        $link = str_replace( '{tracking_id}', $tracking, $link);
        /// poi accorcia il link
        $link = ( new Shortener )->generateShortLink( $link ) ;

        return str_replace([ '{{ url }}', '{{ ga_event }}' ], [ $link, $ga_event ], $template);
    }


}
