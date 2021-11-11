<?php


namespace BLZ_AFFILIATION\Utils;

class Shortener {

    /**
     * Effettua una chiamata al servizio per la creazione degli shortlink
     */
    static function generateShortLink($link){

        $username="techteam";
        $password="trellotrello";
        $baseurl=parse_url($_SERVER[HTTP_HOST], PHP_URL_HOST); //preg_replace("/^(.*?)\.(.*)$/","$2",$_SERVER[HTTP_HOST]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://shortener.".$baseurl."/yourls-api.php?baseurl=".$baseurl."&username=".$username."&action=shorturl&format=json&password=".$password."&url=".urlencode($link),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $short_data = json_decode(curl_exec($curl));
        //print_r($short_data);
        curl_close($curl);
        return ($short_data->shorturl) ? ($short_data->shorturl) : $link;

    }

}
