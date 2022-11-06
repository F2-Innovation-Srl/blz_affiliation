<?php


namespace BLZ_AFFILIATION\Utils;

class Shortener {

    /**
     * Effettua una chiamata al servizio per la creazione degli shortlink
     */
    static function generateShortLink($link){

        $username="techteam";
        $password="trellotrello";
        $baseurl=preg_replace("/^(.*?)\.(.*)$/","$2",$_SERVER["HTTP_HOST"]);
       
        $curl = curl_init();
//die("https://shortener.".$baseurl."/yourls-api.php?baseurl=".$baseurl."&username=".$username."&action=shorturl&format=json&password=".$password."&url=".urlencode($link));
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://shortener.".$baseurl."/yourls-api.php?baseurl=".$baseurl."&username=".$username."&action=shorturl&format=json&password=".$password."&url=".urlencode($link),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HEADER => 1,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        // Then, after your curl_exec call:
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $findme = 'X-GG-Cache-Status: MISS';

        if (strpos($header, $findme) !== false) 
            error_log("###HEADER MISS###: ".$url, 0);

        $short_data = json_decode($body);
        curl_close($curl);
        return (isset($short_data->shorturl)) ? ($short_data->shorturl) : $link;

    }

}
