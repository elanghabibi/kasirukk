<?php

class Controller
{
    public function view($viewPath, $data = [], $layout = "main")
    {
        extract($data);

        $view = "../app/views/" . $viewPath . ".php";
        require_once "../app/views/layouts/" . $layout . ".php";
    }

    public static function model($model)
    {
        require_once "../app/models/" . $model . ".php";
        return new $model;
    }

    public function redirect($redirect) {
        header("Location: ".BASEURL.$redirect);
    }
}





?>