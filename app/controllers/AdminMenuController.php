<?php

class AdminMenuController extends Controller {
    public function index()
    {
        $status = $_GET['status'] ?? 'aktif';
        $keyword = trim($_GET['search'] ?? '');

        $data["title"] = "Kelola Menu";

        $menuModel = $this->model("Menu");

        if ($status === 'nonaktif') {
            $menus = $menuModel->onlyTrashed();
        } else {
            if ($keyword !== '') {
                $menus = $menuModel->search($keyword, [
                    "nama",
                ]);
            } else {
                $menus = $menuModel->latest();
            }
        }

        $dataKategori = $this->model("Kategori")->all();

        $data["dataMenu"] = $menus;
        $data["dataKategori"] = $dataKategori;
        $data["status"] = $status;
        $data["keyword"] = $keyword;

        $this->view("admin/menu/index", $data, "admin");
    }

    public function destroy($id)
    {
        $menuModel = $this->model("Menu");

        if ($menuModel->destroy($id) > 0) {
            Flasher::setFlash("Menu berhasil dihapus!");
            return $this->redirect("/admin/menu");
        } else {
            Flasher::setFlash("Gagal menghapus menu", "error", "Gagal!");
            return $this->redirect("/admin/menu");
        }
    }

    public function nonaktifkan($id)
    {
        $menuModel = $this->model("Menu");

        if ($menuModel->softDelete($id) > 0) {
            Flasher::setFlash("Menu berhasil dinonaktifkan!");
            return $this->redirect("/admin/menu");
        } else {
            Flasher::setFlash("Gagal menonaktifkan menu", "error", "Gagal!");
            return $this->redirect("/admin/menu");
        }
    }

    public function aktifkan($id)
    {
        $menuModel = $this->model("Menu");

        if ($menuModel->restore($id) > 0) {
            Flasher::setFlash("Menu berhasil diaktifkan!");
            return $this->redirect("/admin/menu");
        } else {
            Flasher::setFlash("Gagal mengaktifkan menu", "error", "Gagal!");
            return $this->redirect("/admin/menu");
        }
    }

}

// Buat Fitur Tambah Menu Edit Menu dll
?>