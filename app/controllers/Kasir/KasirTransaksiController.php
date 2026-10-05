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

        $cart = $_SESSION['cart'] ?? [];

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


        $kategoriModel = $this->model("Kategori");
        $menuModel = $this->model("Menu");

        $menuModel->withKategori();

        $kategoriData = $kategoriModel->all();
        $menuData = $menuModel->all();

        $data['kategoriData'] = $kategoriData;
        $data['filterKategori'] = $filterKategori;
        $data['menuData'] = $menuData;

        $data['cart'] = $cart;

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
                'nama_kategori' => $menu['nama_kategori'],
                'nama_menu' => $menu['nama'],
                'harga' => $menu['harga'],
                'foto' => $menu['foto'],
                'qty' => 1,
                'subtotal' => $menu['harga']
            ];
        }

        return $this->redirect('/kasir/transaksi');
    }

    public function updateCart()
    {
        $menuId = (int) ($_POST['menu_id'] ?? 0);
        $action = $_POST['action'] ?? '';

        if ($menuId <= 0) {
            return $this->redirect('/kasir/transaksi');
        }

        if (!isset($_SESSION['cart'])) {
            return $this->redirect('/kasir/transaksi');
        }

        foreach ($_SESSION['cart'] as $index => &$item) {

            if ($item['menu_id'] === $menuId) {

                if ($action === 'increase') {
                    $item['qty']++;
                }

                if ($action === 'decrease') {
                    $item['qty']--;
                }

                if ($item['qty'] <= 0) {
                    // $item['qty'] = 1;

                    if ($item['menu_id'] === $menuId) {

                        unset($_SESSION['cart'][$index]);

                        break;
                    }
                }

                $item['subtotal'] =
                    $item['harga'] * $item['qty'];

                break;
            }
        }

        unset($item);

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
}
