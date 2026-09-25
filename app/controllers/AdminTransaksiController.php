<?php

class AdminTransaksiController extends Controller
{
    public function index()
    {
        $dataTransaksi = $this->model('Transaksi')->getWithDetails();

        $data['title'] = 'Kelola Transaksi';
        $data['dataTransaksi'] = $dataTransaksi;
        $data['totalTransaksi'] = $this->model("Transaksi")->count();
        $data['totalPendapatan'] = $this->model("Transaksi")->sum('total');

        $this->view(
            'admin/transaksi/index',
            $data,
            'admin'
        );
    }

    public function struk($id)
    {
        // var_dump($this->model("Transaksi")->withDetailTransaksi()->where('id', $id)->get());
        // exit;
        $transaksiModel = $this->model("Transaksi");

        $transaksi = $transaksiModel->getDetailById($id);

        if (!$transaksi) {
            Flasher::setFlash(
                "Transaksi tidak ditemukan",
                "error",
                "Gagal!"
            );

            return $this->redirect("/admin/transaksi");
        }

        $data = [
            'transaksi' => $transaksi
        ];

        $this->view('admin/transaksi/struk', $data, "struk");
    }
}
