<?php

namespace BLZ\Hooks;

use BLZ\Utils\Shortener;

class AffiliateContentHooks {

    static function init() {

        // Add the custom columns to the posts post type:
        add_filter( 'the_content', [ get_called_class(), 'fix_editorial_link'] );

    }

    static function fix_editorial_link( $content ) {

         // Check if we're inside the main loop in a single Post.
         if ( is_singular() ) { 
            $marketPlace = "amazon";
    
            global $post;
            $categories = get_the_category($post->ID);
            $category = (isset($categories[0])) ? $categories[0]->slug : "";
           
            
            /// aggiunge paid al marketplace
            $is_paid = has_tag( "paid", $post ) ;

            $author_id = $post->post_author;
            $autore = (!empty(get_the_author_meta('user_nicename', $author_id))) ? get_the_author_meta('user_nicename', $author_id) : "author" ;
            $author = (!empty(get_field('analitics_name', 'user_'.$author_id))) ? get_field('analitics_name', 'user_'.$author_id) : $autore;
            if (has_tag("ebay") )        $marketPlace = "ebay";
            if (has_tag("trovaprezzi") ) $marketPlace = "trovaprezzi";
            
            /// aggiunge '-paid' se presente il tag
            $marketPlace .= $is_paid ? "-paid" : "";
            $trackingID       = "mtz cta vg " . $category . " ";
            $trackingIDAuthor = $trackingID . $author . " ";
            $print_amp = (is_amp_endpoint()) ? " amp" : ""; // per tracciamento AMP
             
            // PRETTILINKS
            $content = preg_replace_callback('/<a([^>]*?)href="https:\/\/([^"]*?)mtz-editorial\/([^"]*?)"(.*?)>/',
            function($match) use ($trackingID,$is_paid) {
                return '<a target="_blank" data-vars-affiliate="' . strtolower($trackingID).strtolower($match[3]).($is_paid ? "-paid" : "").'" class="affiliation-intext" '.$match[1].'href="https://'.$match[2].'mtz-editorial/'.$match[3].'"'.$match[4].'>';
            }, $content, -1);
            
            // SE IL LINK HA UN -- NELLA URL ALLORA PRENDO IL MERCHANT 
            $content = preg_replace_callback('/<a([^>]*?)href="https:\/\/([^"]*?)mtz-editorial\/([^"]*?)--([^"]*?)"(.*?)>/',
            function($matches) use ($trackingID,$is_paid) {
                return  '<a target="_blank" data-vars-affiliate="'.strtolower($trackingID).strtolower($matches[3]).($is_paid ? "-paid" : "").'" class="affiliation-intext" '.$matches[1].'href="https://'.$matches[2].'mtz-editorial/'.$matches[3].'--'.$matches[4].'"'.$matches[5].'>';
            }, $content, -1);
            // FINE PRETTILINKS

            

            $merchants = [
                (object) [ 'signature' => 'amz',     'tracking_code' => 'amazon' . ($is_paid ? '-paid' : '') ],
                (object) [ 'signature' => 'ebay.us', 'tracking_code' => 'ebay'   . ($is_paid ? '-paid' : '') ]
            ];

            foreach( $merchants as $merchant ) {

                $regexp = '/<a([^>]*?)href="https:\/\/'.$merchant->signature.'([^"]*?)"(.*?)>/';
                
                $complete_tracking_id = $trackingIDAuthor . $merchant->tracking_code . $print_amp;
                
                $link = '<a target="_blank" data-vars-affiliate="' . $complete_tracking_id .'" class="affiliation-intext" $1href="https://' . $merchant->signature . '$2"$3>';
                
                $content = preg_replace( $regexp, $link, $content, -1 );
            }            
            
            if ( has_tag("offerte")) $content = preg_replace('/<a([^>]*?)href="https:\/\/bit([^"]*?)"(.*?)>/','<a target="_blank" data-vars-affiliate="'.$trackingIDAuthor.$marketPlace.$print_amp.'" class="affiliation-intext" $1href="https://bit$2"$3>',$content,-1);
              
            // HOOK TROVAPREZZI
            //es: https://www.trovaprezzi.it/goto/444565361?impression=Vzl6eFN4eHZRNFR6eHNPUVlyQmdxZVZlc3J2dWFnM0NFUmtiY3ZWSHUyU0FYWGp6QnNXMCtRPT01&search=QXBwbGUgaVBob25lIDEzIFBybyBNYXgJNw2&pos=1&nb_results=59
            $content = preg_replace('/<a([^>]*?)href="https:\/\/www.trovaprezzi.it\/goto([^"]*?)"(.*?)>/','<a target="_blank"  data-vars-affiliate="'.$trackingIDAuthor.'trovaprezzi'.$print_amp.'" class="affiliation-intext" $1href="https://www.trovaprezzi.it/goto$2&sid=&utm_medium=referral&utm_source=telefoninoit"$3>',$content,-1);
                
            //$content = preg_replace('/<a(.*?)href="https:\/\/([^"]*?)mtz-editorial\/([^"]*?)"(.*?)>/','<a target="_blank"  data-vars-affiliate="'.strtolower($trackingID).'$3" class="'.strtolower($trackingID).'$3" $1href="https://$2mtz-editorial/$3"$4>',$content,-1);
          
              // TRACKING AMANZON/EBAY DIRECT LINK
              
              //AMAZON
              // es: https://www.amazon.it/nuovo-echo-dot-4a-generazione-altoparlante-intelligente-con-alexa-antracite/dp/B084DWG2VQ/ref=sr_1_1?__mk_it_IT=%C3%85M%C3%85%C5%BD%C3%95%C3%91&dchild=1&keywords=amazon+echo+dot&qid=1623859095&sr=8-1
        
              //EBAY
              // es: https://www.ebay.it/itm/144064061772?_trkparms=aid%3D777008%26algo%3DPERSONAL.TOPIC%26ao%3D1%26asc%3D20200708143445%26meid%3D4b5883dd5cd64d3099f08b2c68959508%26pid%3D101251%26rk%3D1%26rkt%3D1%26itm%3D144064061772%26pmt%3D0%26noa%3D1%26pg%3D2380057%26algv%3DPersonalizedTopicsV2WithMLR%26brand%3DOPPO&_trksid=p2380057.c101251.m47269&_trkparms=pageci%3A10d43c13-cebc-11eb-b38d-5e0981539d48%7Cparentrq%3A158e0feb17a0a7b1ce30ce53ffeea13c%7Ciid%3A1   
             
                
                 
              if (!empty($category)){
                $amazon_traking_id = get_field('amazon_traking_id_'.$category, 'user_'.$author_id);
                $ebay_traking_id = get_field('ebay_traking_id_'.$category, 'user_'.$author_id);
                if (!empty($amazon_traking_id)) get_field('amazon_traking_id', 'user_'.$author_id);
                if (!empty($ebay_traking_id)) get_field('amazon_traking_id', 'user_'.$author_id);
              }else{
                $amazon_traking_id = get_field('amazon_traking_id', 'user_'.$author_id);
                $ebay_traking_id = get_field('ebay_traking_id', 'user_'.$author_id);
              }


              $data_trackings = [
                  "amazon" =>  "?tag=".$amazon_traking_id,
                  "ebay"   =>  "?mkcid=1&mkrid=724-53478-19255-0&siteid=101&campid=5338741871&customid=".$ebay_traking_id.($is_paid ? "-paid" : "")."&toolid=10001&mkevt=1" 
              ];
              if (strpos($data_trackings["amazon"],"-21") === false) $data_trackings["amazon"] .= "-21";
              if ($is_paid) $data_trackings["amazon"] = str_replace("-21","-paid-21",$data_trackings["amazon"]);

              if (is_amp_endpoint()){
                $data_trackings["amazon"] = str_replace("-21","-amp-21",$data_trackings["amazon"]);
                $data_trackings["ebay"] = str_replace("&toolid","Amp&toolid",$data_trackings["ebay"]);
              }
              
              foreach ($data_trackings as $key => $value){
                
                  $content = preg_replace_callback('/<a([^>]*?)href="https:\/\/www.'.$key.'.it([^"]*?)"(.*?)>/',
                    function($matches) use ($trackingID,$trackingIDAuthor,$key,$value,$print_amp,$is_paid) {
                        $link= 'https://www.'.$key.'.it' . ((strpos($matches[2], "?") !== false) ? explode("?",$matches[2])[0].$value : $matches[2].$value);
                        if($key == "amazon") 
                            if (strpos($matches[2], "?") !== false)  
                                foreach(explode("&",str_replace("&amp;","&",explode("?",$matches[2])[1])) as $par) // REMOVE OLD TAG ATTRIVUTES
                                    if (strpos($par, "tag=") === false)
                                        $link.= "&".$par;
                        if ($key != "ebay")
                            $link = Shortener::generateShortLink($link);
                        return '<a rel="sponsored" target="_blank" data-vars-affiliate="'.$trackingIDAuthor.$key.($is_paid ? "-paid" : "").$print_amp.'" class="affiliation-intext" '.$matches[1].'href="'.$link.'"'.$matches[3].'>';
                    }, $content, -1);
                     
              }
              
        }  

        return $content;
    }

}
