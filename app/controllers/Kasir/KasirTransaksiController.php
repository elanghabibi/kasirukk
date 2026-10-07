<?php

class KasirTransaksiController extends Controller
{
    public function index()
    {
        $kategoriModel = $this->model("Kategori");
        $menuModel = $this->model("Menu");

        $filterKategori = $_GET['kategori'] ?? "semua";
        $keyword = trim($_GET['search'] ?? '');
        $kategori = $_GET['kategori'] ?? 'semua';

        $data['title'] = "Transaksi";
        $data['back'] = "/kasir";
        $data['keyword'] = $keyword;


        $cart = $menuModel->getCartItems($_SESSION['cart']) ?? [];
        $totalItem = array_sum(
            array_column($_SESSION['cart'], 'qty')
        );

        $data['cart'] = $cart;
        $data['totalItem'] = $totalItem;

        $totalBeforeTax = 0;
        $nilaiTax = 0.1;

        foreach ($cart as $item) {
            $totalBeforeTax += $item['subtotal'];
        }

        $tax = $totalBeforeTax * $nilaiTax;
        $total = $totalBeforeTax + $tax;

        $data['totalBeforeTax'] = $totalBeforeTax;
        $data['tax'] = $tax;
        $data['total'] = $total;

        $kategoriData = $kategoriModel->orderBy('created_at', 'ASC');

        if ($kategori == "semua") {
            $menuModel->withKategori();
        } else {
            $kategoriFilter = $kategoriModel->where("slug", $kategori)->get();

            // var_dump($kategoriFilter);
            // exit;
            $menuModel->withKategori()->where('kategori_id', $kategoriFilter[0]['id']);
        }

        if ($keyword !== '') {
            $menuModel->search($keyword, ['nama']);
        }

        $menuData = $menuModel->where("ketersediaan", "tersedia")->get();

        $data['kategoriData'] = $kategoriData;
        $data['filterKategori'] = $filterKategori;
        $data['menuData'] = $menuData;

        $this->view("kasir/transaksi/index", $data, "kasir");
    }

    public function addToCart()
    {
        $menuId = (int) ($_POST['menu_id'] ?? 0);

        if ($menuId <= 0) {
            return $this->redirect('/kasir/transaksi');
        }

        $menuModel = $this->model('Menu');

        $menu = $menuModel->find($menuId);

        if (!$menu) {
            return $this->redirect('/kasir/transaksi');
        }

        // Buat cart jika belum ada
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;

        foreach ($_SESSION['cart'] as &$item) {

            if ($item['menu_id'] === $menuId) {

                $item['qty']++;

                $item['subtotal'] =
                    $item['harga'] * $item['qty'];

                $found = true;

                break;
            }
        }

        unset($item);

        if (!$found) {

            $_SESSION['cart'][] = [
                'menu_id' => $menu['id'],
                'qty' => 1
            ];
        }

        return $this->redirect('/kasir/transaksi');
    }

    public function updateCart()
    {
        $menuModel = $this->model('Menu');

        $menuId = (int) ($_POST['menu_id'] ?? 0);
        $action = $_POST['action'] ?? '';

        if ($menuId <= 0) {
            return $this->redirect('/kasir/transaksi');
        }

        if (!isset($_SESSION['cart'])) {
            return $this->redirect('/kasir/transaksi');
        }

        foreach ($_SESSION['cart'] as $index => &$item) {

            if ((int) $item['menu_id'] !== $menuId) {
                continue;
            }

            $menu = $menuModel->find($menuId);

            if ($action === 'increase') {

                if (
                    $menu &&
                    $menu['ketersediaan'] === 'tersedia' &&
                    empty($menu['deleted_at'])
                ) {
                    $item['qty']++;
                } else {
                    Flasher::setFlash(
                        "Menu tidak dapat ditambah",
                        "error",
                        "Menu habis!"
                    );
                }
            } elseif ($action === 'decrease') {

                $item['qty']--;

                if ($item['qty'] <= 0) {
                    unset($_SESSION['cart'][$index]);
                }
            }

            break;
        }

        unset($item);

        $_SESSION['cart'] = array_values($_SESSION['cart']);

        return $this->redirect('/kasir/transaksi');
    }

    public function removeFromCart()
    {
        $menuId = (int) ($_POST['menu_id'] ?? 0);

        if ($menuId <= 0) {
            return $this->redirect('/kasir/transaksi');
        }

        if (!isset($_SESSION['cart'])) {
            return $this->redirect('/kasir/transaksi');
        }

        foreach ($_SESSION['cart'] as $index => $item) {

            if ($item['menu_id'] === $menuId) {

                unset($_SESSION['cart'][$index]);

                break;
            }
        }

        $_SESSION['cart'] = array_values($_SESSION['cart']);

        return $this->redirect('/kasir/transaksi');
    }

    public function clearCart()
    {
        $_SESSION['cart'] = [];

        return $this->redirect('/kasir/transaksi');
    }

    public function pembayaran()
    {
        $menuModel = $this->model("Menu");
        $cart = $menuModel->getCartItems($_SESSION['cart']) ?? [];

        if (empty($cart)) {
            Flasher::setFlash("Keranjang belum diisi.", "error", "Keranjang kosong!");
            return $this->redirect('/kasir/transaksi');
        }

        $data['title'] = "Pembayaran";
        $data['back'] = "/kasir/transaksi";


        $totalItem = array_sum(
            array_column($_SESSION['cart'], 'qty')
        );

        $data['cart'] = $cart;
        $data['totalItem'] = $totalItem;

        $totalBeforeTax = 0;
        $nilaiTax = 0.1;

        foreach ($cart as $item) {
            $totalBeforeTax += $item['subtotal'];
        }

        $tax = $totalBeforeTax * $nilaiTax;
        $total = $totalBeforeTax + $tax;

        $data['totalBeforeTax'] = $totalBeforeTax;
        $data['tax'] = $tax;
        $data['total'] = $total;

        $this->view("kasir/transaksi/pembayaran", $data, "kasir");
    }

    public function store()
    {
        $action = $_POST['action'] ?? 'save';

        $transaksiModel = $this->model("Transaksi");
        $detailTransaksiModel = $this->model("DetailTransaksi");
        $menuModel = $this->model("Menu");

        // =========================
        // 1. Ambil cart
        // =========================

        $sessionCart = $_SESSION['cart'] ?? [];

        if (empty($sessionCart)) {
            Flasher::setFlash(
                "Keranjang masih kosong",
                "error",
                "Gagal!"
            );

            return $this->redirect("/kasir/transaksi");
        }

        // Ambil data terbaru dari database
        $cart = $menuModel->getCartItems($sessionCart);

        if (empty($cart)) {
            Flasher::setFlash(
                "Menu dalam keranjang sudah tidak tersedia",
                "error",
                "Gagal!"
            );

            return $this->redirect("/kasir/transaksi");
        }

        // =========================
        // 2. Validasi form
        // =========================

        $errors = Validator::validate($_POST, [
            "tipe_order" => "required",
            "tanggal_transaksi" => "required",
            "bayar" => "required"
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            return $this->redirect("/kasir/transaksi/pembayaran");
        }

        // =========================
        // 3. Hitung total
        // =========================

        $totalBeforeTax = 0;

        foreach ($cart as $item) {
            $totalBeforeTax += $item['subtotal'];
        }

        $nilaiTax = 0.1;
        $tax = $totalBeforeTax * $nilaiTax;
        $total = $totalBeforeTax + $tax;

        $bayar = (float) $_POST['bayar'];
        $kembalian = $bayar - $total;

        // =========================
        // 4. Buat kode transaksi
        // =========================

        $urutan = $transaksiModel->getUrutanHariIni();
        $kodeTransaksi = Helpers::formatKodeTransaksi($urutan);

        // =========================
        // 5. Data transaksi
        // =========================

        $transaksiData = [
            "kode_transaksi" => $kodeTransaksi,
            "user_id" => Auth::user()['id'],
            "nama_pelanggan" => $_POST['customer'] == "" ? "Umum" : $_POST['customer'],
            "tipe_order" => $_POST['tipe_order'],
            "no_meja" => $_POST['no_meja'],
            "keterangan" => $_POST['keterangan'],
            "tanggal_transaksi" => $_POST['tanggal_transaksi'],
            "subtotal" => $totalBeforeTax,
            "pajak" => $tax,
            "total" => $total,
            "tunai" => $bayar,
            "kembalian" => $kembalian
        ];

        // =========================
        // 6. Simpan transaksi
        // =========================

        if ($transaksiModel->store($transaksiData) <= 0) {

            Flasher::setFlash(
                "Gagal membuat transaksi",
                "error",
                "Gagal!"
            );

            return $this->redirect("/kasir/transaksi/pembayaran");
        }

        // =========================
        // 7. Ambil transaksi yang baru dibuat
        // =========================

        $transaksi = $transaksiModel->firstWhere(
            'kode_transaksi',
            $kodeTransaksi
        );

        if (!$transaksi) {

            Flasher::setFlash(
                "Transaksi berhasil dibuat tetapi data transaksi tidak ditemukan",
                "error",
                "Gagal!"
            );

            return $this->redirect("/kasir/transaksi");
        }

        // =========================
        // 8. Simpan detail transaksi
        // =========================

        foreach ($cart as $item) {

            $detailTransaksiModel->store([
                "transaksi_id" => $transaksi['id'],
                "menu_id" => $item['menu_id'],
                "nama_menu" => $item['nama_menu'],
                "harga_menu" => $item['harga'],
                "qty" => $item['qty'],
                "subtotal" => $item['subtotal']
            ]);
        }

        // =========================
        // 9. Kosongkan cart
        // =========================

        $_SESSION['cart'] = [];

        // =========================
        // 10. Redirect sesuai tombol
        // =========================

        Flasher::setFlash(
            "Transaksi berhasil dibuat!"
        );

        if ($action === 'save_print') {
            return $this->redirect(
                "/riwayat-transaksi/struk/" . $transaksi['id']
            );
        }

        return $this->redirect("/kasir/transaksi");
    }

    public function riwayat()
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

        $data['back'] = "/kasir";

        $data['totalTransaksi'] = $this
            ->model('Transaksi')
            ->count();

        $data['totalPendapatan'] = $this
            ->model('Transaksi')
            ->sum('total');

        $data['keyword'] = $keyword;

        $this->view(
            'kasir/transaksi/riwayat',
            $data,
            'kasir'
        );
    }
}
