<?php

class AdminTransaksiController extends Controller
{
    public function index()
    {
        $keyword = trim($_GET['search'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $banyakDataPerPage = 10;

        $transaksiModel = $this->model('Transaksi');

        if ($keyword !== '') {
            $transaksiModel->search(
                $keyword,
                ['kode_transaksi', 'nama_pelanggan']
            );
        }

        $pagination = $transaksiModel->paginateWithDetails(
            $banyakDataPerPage,
            $page
        );

        $data['title'] = 'Riwayat Transaksi';

        $data['dataTransaksi'] = $pagination['data'];

        $data['pagination'] = $pagination;

        $data['totalTransaksi'] = $this
            ->model('Transaksi')
            ->count();

        $data['totalPendapatan'] = $this
            ->model('Transaksi')
            ->sum('total');

        $data['keyword'] = $keyword;

        $this->view(
            'admin/transaksi/index',
            $data,
            'admin'
        );
    }

    public function struk($kodeTransaksi)
    {
        // var_dump($this->model("Transaksi")->withDetailTransaksi()->where('id', $id)->get());
        // exit;
        $transaksiModel = $this->model("Transaksi");

        $t = $transaksiModel->where("kode_transaksi", $kodeTransaksi)->first();

        $transaksi = $transaksiModel->getDetailById($t['id']);

        if (!$transaksi) {
            Flasher::setFlash(
                "Transaksi tidak ditemukan",
                "error",
                "Gagal!"
            );

            return $this->redirect("/" . Auth::user()['role'] . "/riwayat-transaksi");
        }

        $data = [
            'transaksi' => $transaksi
        ];

        $this->view('admin/transaksi/struk', $data, "struk");
    }
}
