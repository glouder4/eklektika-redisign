<?php
    namespace OnlineService\B24;
    class Request{
        protected function sendB24Request($method, $params){
            $queryUrl = URL_B24.'rest/1/w8i2ce68y3wwps17/'.$method.'.json';
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
            return $result["result"];
        }

        protected function newB24Rest($param, $arFields, $select) {
            $qrList = array(
                'fields' => array(),
                'params' => array(),
                'select' => array(),
                'filter' => array()
            );

            $qrList['filter'][$param] = $arFields[$select];
            $qrList['select'][] = $param;

            return $this->sendB24Request("crm.contact.list", $qrList);//$result["result"];
        }
    }