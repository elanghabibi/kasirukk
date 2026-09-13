<?php

class AdminMenuController extends Controller
{
    public function index()
    {
        $status = $_GET['status'] ?? 'aktif';
        $keyword = trim($_GET['search'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $data['title'] = 'Kelola Menu';

        $menuModel = $this->model('Menu');

        // Tentukan mode soft delete berdasarkan status
        $trashedMode = $status === 'nonaktif'
            ? 'onlyTrashed'
            : 'active';

        // Query dasar
        $menuModel->withKategori();

        // Tambahkan pencarian jika keyword tidak kosong
        if ($keyword !== '') {
            $menuModel->search($keyword, ['nama']);
        }

        // Ambil data dengan pagination
        $pagination = $menuModel->paginate(
            10,
            $page,
            $trashedMode
        );

        // Ambil data kategori
        $dataKategori = $this->model('Kategori')->all();

        // Kirim data ke view
        $data['dataMenu'] = $pagination['data'];
        $data['pagination'] = $pagination;
        $data['dataKategori'] = $dataKategori;
        $data['status'] = $status;
        $data['keyword'] = $keyword;

        $this->view(
            'admin/menu/index',
            $data,
            'admin'
        );
    }

    public function store()
    {
        $errors = Validator::validate($_POST, [
            "nama" => "required",
            "kategori_id" => "required",
            "harga" => "required",
            "ketersediaan" => "required"
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            return $this->redirect("/admin/menu");
        }

        $imagePath = Upload::image('foto', 'uploads/foto_menu');

        if ($imagePath) {
            $_POST['foto'] = $imagePath;
        }

        $menuModel = $this->model("menu");

        if ($menuModel->store($_POST) > 0) {
            Flasher::setFlash("Catatan berhasil dibuat!");
            return $this->redirect("/admin/menu");
        } else {
            Flasher::setFlash("Gagal membuat catatan", "error", "Gagal!");
            return $this->redirect("/admin/menu");
        }
    }

    public function edit($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "Menu tidak ditemukan",
                "error",
                "Gagal Mengedit!"
            );

            return $this->redirect("/admin/menu");
        }

        $dataKategori = $this->model('Kategori')->all();
        $menuModel = $this->model("menu");
        $data['dataKategori'] = $dataKategori;
        $data["menu"] = $menuModel->withKategori()->find($id);

        if (!$data["menu"]) {
            Flasher::setFlash(
                "Menu tidak ditemukan",
                "error",
                "Gagal Mengedit!"
            );

            return $this->redirect("/admin/menu");
        }

        $data["title"] = "Edit Menu";
        $this->view("admin/menu/edit", $data, "admin");
    }

    public function update($id = null)
    {

        if (!$id) {
            return $this->redirect('/admin/menu');
        }

        $menuModel = $this->model('Menu');

        // Ambil data menu lama
        $menuLama = $menuModel->find($id);

        if (!$menuLama) {
            Flasher::setFlash(
                "Data menu tidak ditemukan",
                "error",
                "Gagal!"
            );

            return $this->redirect('/admin/menu');
        }

        // Validasi
        $errors = Validator::validate($_POST, [
            "nama" => "required",
            "kategori_id" => "required",
            "harga" => "required",
            "ketersediaan" => "required"
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            return $this->redirect('/admin/menu');
        }

        /*
     * Default:
     * gunakan foto lama
     */
        $_POST['foto'] = $menuLama['foto'];

        /*
     * Cek apakah user mengupload foto baru
     */
        if (
            isset($_FILES['foto']) &&
            $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
        ) {
            $imagePath = Upload::image(
                'foto',
                'uploads/foto_menu'
            );

            if ($imagePath) {

                // Hapus foto lama
                if (!empty($menuLama['foto'])) {
                    $oldFile = dirname(__DIR__, 2) . '/public/' . $menuLama['foto'];

                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                // Gunakan foto baru
                $_POST['foto'] = $imagePath;
            }
        }

        // Update data
        if ($menuModel->update($_POST, $id) > 0) {

            Flasher::setFlash(
                "Menu berhasil diperbarui!"
            );
        } else {

            Flasher::setFlash(
                "Gagal memperbarui menu",
                "error",
                "Gagal!"
            );
        }

        return $this->redirect('/admin/menu');
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
