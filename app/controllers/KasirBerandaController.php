<?php

class KasirBerandaController extends Controller {
    public function index() {
        $data['userModel'] = $this->model("User");
        $data["title"] = "Beranda";

        $user = Auth::user();

        if ($this->model("User")->exists("id", $user['id']) === false) {
            unset($_SESSION['user']);
            $this->redirect("/login");
        }

        $this->view("home", $data, "kasir");
    }
}




?>