<?php

class HomeController extends Controller {
    public function index() {
        if (!Auth::user()) {
            $this->redirect("/login");
        } else {
            $this->redirect("/" . Auth::user()['role']);
        }

        // $data['userModel'] = $this->model("User");
        // $data["title"] = "Beranda";

        // $user = Auth::user();

        // if ($this->model("User")->exists("id", $user['id']) === false) {
        //     unset($_SESSION['user']);
        //     $this->redirect("/login");
        // }

        // $this->view("home", $data, "admin");
    }
}




?>