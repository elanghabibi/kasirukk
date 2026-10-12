<?php

class Controller
{
    public function __construct()
    {
        // Cek Auth otomatis setiap kali controller dipanggil
        if (isset($_SESSION['user'])) {
            $user = Auth::user();

            if ($this->model("User")->exists("id", $user['id']) === false) {
                unset($_SESSION['user']);
                Flasher::setFlash("Akun tidak dapat digunakan", "warning", "Akun nonaktif");
                $this->redirect("/login");
            }
        }
    }
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

    public function redirect($redirect)
    {
        header("Location: " . BASEURL . $redirect);
        exit;
    }
}
