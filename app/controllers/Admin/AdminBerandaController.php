<?php

class AdminBerandaController extends Controller {
    public function index() {
        $data['userModel'] = $this->model("User");
        $data['totalMenu'] = $this->model("Menu")->count();
        $data['totalTransaksi'] = $this->model("Transaksi")->countThisMonth('tanggal_transaksi');
        $data['totalPendapatan'] = $this->model("Transaksi")->sumThisMonth("total", "tanggal_transaksi");
        
        $data["title"] = "Beranda";
        
        $user = Auth::user();
        
        if ($this->model("User")->exists("id", $user['id']) === false) {
            unset($_SESSION['user']);
            $this->redirect("/login");
        }

        $this->view("admin/beranda/index", $data, "admin");
    }
}

?>