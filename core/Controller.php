<?php

namespace app\core;

class Controller {

    public function render($view, $params = []) {

        return Application::$app->router->renderView($view, $params);
    }

    public function renderComponent($view, $components, $params = []) {

        return Application::$app->router->renderComponent($view, $components, $params);
    }

    public function renderOnlyView($view, $params = []) {

        return Application::$app->router->renderOnlyView($view, $params);
    }
}