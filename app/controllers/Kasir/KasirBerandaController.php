<?php

class KasirBerandaController extends Controller {
    public function index() {
        $data['userModel'] = $this->model("User");
        $data["title"] = Settings::set()[0]['nama_restoran'];

        $data['totalMenu'] = $this->model("Menu")->count();
        $data['totalTransaksi'] = $this->model("Transaksi")->countThisMonth('tanggal_transaksi');
        $data['totalPendapatan'] = $this->model("Transaksi")->sumThisMonth("total", "tanggal_transaksi");

        $this->view("kasir/beranda/index", $data, "kasir");
    }
}




?>