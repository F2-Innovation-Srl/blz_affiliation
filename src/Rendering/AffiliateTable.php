<?php

namespace BLZ_AFFILIATION\Rendering;

/**
 * 
 * Ritorna i dati della tabella di affiliazione nella pagina
 * 
 */
class AffiliateTable {

    function __construct() {

        // Add the shortcode to print the links
        add_shortcode( 'affiliate_table', [ $this, 'print'] );
    }

    
    public function print ( $atts, $content, $tag ) {

        $table_id = $atts['id'];

        $table = $this->getTable( $table_id );

        $this->render( $table );
    }


    /**
     * Costruisce il prefisso del tracciamento nel formato
     *
     * tabella <tag> <speciale slug> <page|''>
     *
     * @return void
     */
    private function getTrackingPrefix() {

        $post = get_post($this->post_id);

        /// costruisce il post tag con i seguenti formati
        /// ''
        /// guida
        /// guida-nomespeciale
        /// nomespeciale
        $post_tag = [
            $post->post_type,
            $this->getSpecialeSlug()
        ];

        /// effettua il merge di tag e speciale
        $tag_slug = implode('-', array_values( array_filter( $post_tag, function( $item ) { return $item != ''; } ) ));

        return 'tabella '.$tag_slug ;
    }


    /**
     * Return the table's data
     *
     * @param integer $table_id
     * @return array the table's data
     */
    public function getTable( int $table_id ) {

        if( !have_rows('affiliate_table_row', $table_id) ) return [];

        $table = [];
        $id=1;

        $tracking_prefix = $this->getTrackingPrefix();

        // $amp = is_amp_endpoint() ? ' amp' : '';
        $amp = '';

        while ( have_rows('affiliate_table_row', $table_id) ) : the_row();

            /// abbiamo scelto di non mettere i title
            // $title = Strings::slugify( get_sub_field('title') );

            /// aggiunge al tracking
            /// posizione <pos> <titolo> <amp|''>
            $tracking = $tracking_prefix . ' posizione ' . $id . /*' ' . $title .*/ $amp;

            $table[]= [
                "table_id"       => $id++,
                "table_title"    => $title,
                "table_tracking" => $tracking,
                "table_img"      => get_sub_field('image'),
                "table_text"     => get_sub_field('text'),
                "table_rating"   => get_sub_field('rating'),
                "table_cta_text" => get_sub_field('cta'),
                "table_link"     => get_sub_field('link'),
                "table_link_amp" => get_sub_field('link_amp'),
            ];

        endwhile;

        return $table;
    }

    private function getSpecialeSlug() {

        if( !taxonomy_exists('speciale') ) {
            return '';
        }

        $terms = wp_get_post_terms( $this->post_id, 'speciale' );

        if(is_wp_error($terms)) {
            return '';
        }

        if( empty($terms) ) return '';

        return $terms[0]->slug;
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
                    $SettingsData = new SettingsData($this->postData,"blz_table",(new Request(["marketplace" => $item->table_title])));
                    $link = $this->FillTemplate( $item->table_link, $SettingsData->getGAEvent(), $SettingsData->getTrackingID(), $SettingsData->getTemplate() );
                    
                ?>

                    <li data-vars-affiliate="<?=$item->table_tracking?>">
                            <?=$link?>
                            
                            <div class="col col-1 col-sm-12 col-middle">
                                <div class="rating_index"><?=$item->table_id?></div>
                            </div>
                        
                            <div class="col col-3 col-sm-12 col-middle">
                                <div class="rating_image">
                                    <img src="<?=$item->table_img?>" class="img-cover" alt="<?=$item->table_title?>">
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
