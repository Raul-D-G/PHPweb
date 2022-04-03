<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;

class SController extends Controller {

    private string $scroll_id = '';

    public function files(Request $request, $queryParam = '') {

        if ($queryParam) {
            $files = $this->getScroll($queryParam['scroll']);

        }
        else {
            $files = $this->getFiles();
        }


        return $this->renderComponents($files);
    }

    public function getFiles() {

        $params = [
            "index" => "test",
            "scroll" => "1m",
            "size" => 3,
            "body"   => [
                "query" => [
                    'match_all' => new \stdClass()
                ]
            ]
        ];

        $response = Application::$ES_CLIENT->search($params);

        if (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {

            $this->scroll_id = $response['_scroll_id'];

            return $response['hits']['hits'];
        }
        return '';
    }

    public function getScroll($scroll) {

        $response = Application::$ES_CLIENT->scroll([
            'body' => [
                'scroll_id' => $scroll,  //...using our previously obtained _scroll_id
                'scroll'    => '1m'        // and the same timeout window
            ]
        ]);


        if (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {

            $this->scroll_id = $response['_scroll_id'];

            return $response['hits']['hits'];
        }
    }

    public function search() {
        return $this->render('search');
    }

    public function handleSearch(Request $request) {
        $body = $request->getBody();
        $search = $body['search'];

        $params = [
            'index' => 'test',
            "size" => 3,
            'body'  => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [ 'match' => [ 'headline' => $search ] ],
                            [ 'match' => [ 'genres' =>  $search] ],
                        ]
                    ]
                ]
            ]
        ];

        $results = Application::$ES_CLIENT->search($params);

        return $this->renderComponents($results['hits']['hits']);

    }


    private function renderComponents($files) {
        $components = '';
        foreach ($files as $file) {

            if ($file['_source']['duration']) {
                $file['_source']['duration'] = $this->hoursAndMins($file['_source']['duration'],'%02d hours %02d minutes');
            }

            $fileCard =  $this->renderOnlyView('file', $file['_source']);
            $components .= $fileCard;
        }
        return $this->renderComponent('files', $components, ['scroll' => $this->scroll_id]);
    }

    private function hoursAndMins($time, $format = '%02d:%02d')
    {

        $minutes = floor(($time%3600)/60);
        $hours = floor(($time%86400)/3600);

        return sprintf($format, $hours, $minutes);
    }
}