<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'vendor/autoload.php';
 
use GuzzleHttp\Client;
if ($_GET) {
    $parametros = explode('/', $_GET['url']);;
    $from = $parametros[2];
    $to = $parametros[3];
    $amount = $parametros[1];
    $rate = $parametros[4];
    switch ($to) {
        case 'BRL':
            $simbolo = 'R$';
            break;
        case 'USD':
            $simbolo = '$';
            break;
        case 'EUR':
            $simbolo = '€';
            break;
        default:
            $simbolo = '$';
            break;
    }
    $client = new Client([
        // Base URI is used with relative requests
        'base_uri' => 'https://api.exchangeratesapi.io/',
    ]);
    
    // get all rates
    $response = $client->request('GET', 'latest', [
        'query' => [
            'base' => $from,
            'symbols' => $to,
        ]
    ]);
    
    if($response->getStatusCode() == 200) {
        $body = $response->getBody();
        $arr_body = json_decode($body, true);
        $valor=$arr_body['rates'][$to];
        $total=$valor * $amount;
        $total= round($total,2);
        $json =['valorConvertido'=>$total,'simboloMoeda'=>$simbolo];
        echo json_encode($json);
    }
}