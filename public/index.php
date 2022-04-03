<?php

require_once __DIR__.'/../../vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()
    ->setHosts(['elasticsearch:9200'])
    ->build();

// Info API
$response = $client->info();


$json = file_get_contents('https://mgtechtest.blob.core.windows.net/files/showcase.json');
$json = mb_convert_encoding($json, 'UTF-8', 'UTF-8');
$obj = json_decode($json);



//var_dump(($obj[0]));
//echo 'Last error: ', json_last_error_msg(), PHP_EOL, PHP_EOL;

$id = $obj[0]->id;

# Indexing a document

 $params = [
     'index' => 'test',
     'id' => $id,
     'body' => $obj[0]
 ];

 $response = $client->index($params);


# Getting a document

 $params = [
     'index' => 'test',
     'id'  => $id

 ];
 $response = $client->get($params);

 var_dump(json_encode($response));
