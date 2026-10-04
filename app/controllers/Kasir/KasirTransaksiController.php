<?php

class KasirTransaksiController extends Controller
{
    public function index()
    {
        $filterKategori = $_GET['kategori'] ?? "semua";
        $keyword = trim($_GET['search'] ?? '');

        $data['title'] = "Transaksi";
        $data['back'] = "/kasir";
        $data['keyword'] = $keyword;

        $kategoriModel = $this->model("Kategori");

        $kategoriData = $kategoriModel->all();

        $data['kategoriData'] = $kategoriData;
        $data['filterKategori'] = $filterKategori;

        $this->view("kasir/transaksi/index", $data, "kasir");
    }
}
