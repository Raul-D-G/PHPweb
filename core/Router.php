<?php

namespace app\core;

class Router {

    public Request $request;
    public Response $response;

    protected array $routes = [];

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback) {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve() {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $queryParam = $this->request->getQueryParam();

        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setStatusCode(404);
            return $this->renderContent('Not Found');
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        if (is_array($callback)) {
            $callback[0] = new $callback[0]();
        }

        if ($queryParam) {
            return call_user_func($callback, $this->request, $queryParam);
        }
        else {
            return call_user_func($callback, $this->request);
        }
    }

    public function renderView($view, $params = []) {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderComponent($view, $components, $params) {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);

        $layoutContent = str_replace('{{content}}', $viewContent, $layoutContent);

        return str_replace('{{component}}', $components, $layoutContent);

    }


    private function renderContent($viewContent) {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent() {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/main.php";
        return ob_get_clean();
    }

    public function renderOnlyView($view, $params) {

        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }

}