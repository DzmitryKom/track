<?php

use GuzzleHttp\Exception\ClientException;

require __DIR__.'/vendor/autoload.php';


//echo"cdasa";die;

$conf=['base_uri'=>'http://track-api.dev/app_dev.php/api/',
    'exeptions'=>false
    ,'headers' => [
        'Accept'     => 'application/json',
    ]
];

$client = new \GuzzleHttp\Client($conf);



$resp = $client->request('GET','tracks',[
//    "body"=>json_encode((array)$body)
]);

$respBody=(string)$resp->getBody();

dump($resp->getHeaders());
dump($respBody);

die;


$track = [
    "make"=>'Mercedes',
    "model"=>'Diesel 456',
    "bodyType"=>'van',
    "miles"=>rand(0,2000000),
];

//var_dump();die;


//try{
    $resp = $client->request('POST','tracks/new',[
        "body"=>json_encode((array)$track)
//        "form_params"=>["client_id"=>'Test SSO',
//            "client_secret"=>'9ac8b337677c93418405e1ee21183f3d',
//            "grant_type"=>'authorization_code',
//            "code"=>$code,
//            "redirect_uri"=>$redirectUrl,],
//        'headers' => [
//            'Accept'     => 'application/json',
//        ]

    ]);
//}catch (ClientException  $e){
//    $error['message']= $e->getMessage();
//    echo ($error['message']);
////    return $this->render(':UserBundle:failed-authorization.html.twig',['error'=>$error]);
//}

$respBody=(string)$resp->getBody();
$trackUrl= $resp->getHeader('Location')[0];

dump($resp);
dump($respBody);
//$respObj= json_decode($respBody);


$client = new \GuzzleHttp\Client($conf);
//
//$body = [
//    "id"=>100
//   ];

$resp = $client->request('GET',$trackUrl,[
//    "body"=>json_encode((array)$body)
]);

$respBody=(string)$resp->getBody();

dump($resp->getHeaders());
dump($respBody);
//$respObj= json_decode($respBody);
