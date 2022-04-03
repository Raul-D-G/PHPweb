<?php

namespace app\core;

use Elasticsearch\ClientBuilder;

class Application {

    public static string $ROOT_DIR;
    public static $ES_CLIENT;

    public Router $router;
    public Request $request;
    public Response $response;
    public static Application $app;


    public function __construct($rootPath) {

        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        self::$ES_CLIENT = $this->initES();
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }


    public function run() {
        echo $this->router->resolve();
    }

    public function initES()
    {
        $client = ClientBuilder::create()
            ->setHosts(['elasticsearch:9200'])
            ->build();

        $obj = $this->getFiles();

//        try {
//            $client->indices()->delete(['index' => 'test']);
//        } catch (\Exception $e) {
//        }
//        $pa = [
//            'index' => 'test',
//            'body' => [
//                'settings' => [
//                    'analysis' => [
//                        'filter' => [
//                            'autocomplete_filter' => [
//                                'type' => 'ngram',
//                                'min_gram' => 2,
//                                'max_gram' => 20]
//                        ],
//                        'analyzer' => [
//                            'autocomplete' => [
//                                'type' => 'custom',
//                                'tokenizer' => 'standard',
//                                'filter' => [
//                                    'lowercase',
//                                    'autocomplete_filter'
//                                ]
//                            ]
//                        ]
//                    ],
//                    "max_ngram_diff" => "20"
//                ]
//            ]
//        ];
//
//        $client->indices()->create($pa);


        if (count($obj)) {
            for ($i = 0; $i <= count($obj); $i++) {
                $params['body'][] = [
                    'index' => [
                        '_index' => 'test',
                        '_type' => '_doc',
                        '_id'    => $obj[$i]->id
                    ]
                ];

                $params['body'][] = $obj[$i];

                // Every 1000 documents stop and send the bulk request
                if ($i % 1000 == 0) {
                    $responses = $client->bulk($params);

                    // erase the old bulk request
                    $params = ['body' => []];

                    // unset the bulk response when you are done to save memory
                    unset($responses);
                }
            }

            // Send the last batch if it exists
            if (!empty($params['body'])) {
                $responses = $client->bulk($params);
            }
        }

        return $client;
    }

    private function getFiles() {
        $json = file_get_contents('https://mgtechtest.blob.core.windows.net/files/showcase.json');
        $json = mb_convert_encoding($json, 'UTF-8', 'UTF-8');
        return json_decode($json);
    }

}