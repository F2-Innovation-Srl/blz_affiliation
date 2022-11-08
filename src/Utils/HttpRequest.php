<?php

namespace BLZ_AFFILIATION\Utils;

class HttpRequest {

    static function getContent( $url ) {

        $url = str_replace(" ", '%20', $url);
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        $response = curl_exec($curl);

        // Then, after your curl_exec call:
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $findme = 'X-GG-Cache-Status: MISS';

        if (strpos($header, $findme) !== false) 
            error_log("###HEADER_MISS###: ".$url, 0);

        curl_close($curl);

        return $body;

        //$ctx = stream_context_create(['https'=>[ 'timeout' => 3]]);
        //return @file_get_contents($url, false, $ctx);
    }

}
