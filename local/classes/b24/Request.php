<?php
    namespace OnlineService\B24;
    class Request{
        protected function sendRequest($params){
            $queryUrl = URL_B24.'local/classes/site_requests_handler.php';
            $curl = curl_init();
            $queryData  = http_build_query($params);
            curl_setopt_array($curl, array(
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $queryUrl,
                CURLOPT_POSTFIELDS => $queryData,
            ));
            if (!$result = curl_exec($curl)) {
                $result = curl_error($curl);
            }
            curl_close($curl);
            $result = json_decode($result, true);

            return $result;
        }
    }