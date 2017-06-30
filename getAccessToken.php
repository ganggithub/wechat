<?php

$appId = 'wx029d1751309779b7';
$appSecrit = '61c1c5810a878eca3a56d2c5ff8af892';

//$rs = send_request('https://api.weixin.qq.com/cgi-bin/token',array('grant_type' => 'client_credential', 'appid' => $appId, 'secret' => $appSecrit));

$img = send_request('https://api.weixin.qq.com/cgi-bin/material/batchget_material', array('access_token' => 'paWhjr2XMgH4r3c5cyqhY-yZUY3AajafnYUZShjeLZmuY9DQPQpz2fnEr28EJBGatRMr2Yr4QLD0tbABoynLyGkUC0KTYXLu_CTen_kzdsCAhKo5uHj9nRPDMplu2fvMZUHdAEARMZ', 'type' => 'image', 'offset' => 0, 'count' => 5));

//var_dump($img);




function send_request($url, $data, $refererUrl = '', $method = 'GET', $contentType = 'application/json', $timeout= 30, $proxy = false) {

    $ch = null;

    if('POST' === strtoupper($method)) {

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        if ($refererUrl) {

            curl_setopt($ch, CURLOPT_REFERER, $refererUrl);

        }

        if($contentType) {

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:'.$contentType));

        }

        if(is_string($data)){

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        } else {

            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        }

    } else if('GET' === strtoupper($method)) {

        if(is_string($data)) {

            $real_url = $url. (strpos($url, '?') === false ? '?' : ''). $data;

        } else {

            $real_url = $url. (strpos($url, '?') === false ? '?' : ''). http_build_query($data);

        }

        $ch = curl_init($real_url);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:'.$contentType));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        if ($refererUrl) {

            curl_setopt($ch, CURLOPT_REFERER, $refererUrl);

        }

    } else {

        $args = func_get_args();

        return false;

    }

    if($proxy) {

        curl_setopt($ch, CURLOPT_PROXY, $proxy);

    }

    $ret = curl_exec($ch);

    $info = curl_getinfo($ch);

    $contents = array(

        'httpInfo' => array(

            'send' => $data,

            'url' => $url,

            'ret' => $ret,

            'http' => $info

        )

    );

    curl_close($ch);

    return $ret;

}

