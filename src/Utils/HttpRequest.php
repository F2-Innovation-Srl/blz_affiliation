<?php

namespace BLZ_AFFILIATION\Utils;

class HttpRequest {

    static function getContent( $url ) {

        $url = str_replace(" ", '%20', $url);
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 1,
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        $data = curl_exec($curl);

        curl_close($curl);

        return $data;

        //$ctx = stream_context_create(['https'=>[ 'timeout' => 3]]);
        //return @file_get_contents($url, false, $ctx);
    }

}
