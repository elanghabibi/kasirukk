<?php

class AdminKategoriController extends Controller
{
    public function index()
    {
        $status = $_GET['status'] ?? 'aktif';
        $keyword = trim($_GET['search'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $banyakPage = 10;

        $data['title'] = 'Kelola Kategori';

        $kategoriModel = $this->model('Kategori');

        // Tentukan status soft delete
        $trashedMode = $status === 'nonaktif'
            ? 'onlyTrashed'
            : 'active';

        // Tambahkan search jika ada keyword
        if ($keyword !== '') {
            $kategoriModel->search(
                $keyword,
                ['nama']
            );
        }

        // Ambil data dengan pagination
        if ($trashedMode == "onlyTrashed") {
            $pagination = $kategoriModel->onlyTrashed()->paginate(
                $banyakPage,
                $page
            );
        } else if ($trashedMode == "active") {
            $pagination = $kategoriModel->paginate(
                $banyakPage,
                $page
            );
        }

        $data['dataKategori'] = $pagination['data'];
        $data['pagination'] = $pagination;
        $data['status'] = $status;
        $data['keyword'] = $keyword;

        $this->view(
            'admin/kategori/index',
            $data,
            'admin'
        );
    }

    public function store()
    {
        $errors = Validator::validate($_POST, [
            "nama" => "required",
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            return $this->redirect("/admin/kategori");
        }

        $kategoriModel = $this->model("Kategori");

        $nama = trim($_POST['nama']);
        $slug = str_replace(' ', '-', strtolower($nama));

        $kategori = $kategoriModel->withTrashed()->where('nama', $nama)->first();
        if ($kategori) {
            if ($kategori['deleted_at'] === null) {
                $_SESSION['errors'] = ['nama' => 'Nama kategori sudah ada.'];
                $_SESSION['old'] = $_POST;
                return $this->redirect("/admin/kategori");
            }
            $kategoriModel->restore($kategori['id']);
            Flasher::setFlash("Kategori berhasil diaktifkan kembali!");
            return $this->redirect("/admin/kategori");
        }

        if ($kategoriModel->store(['nama' => $nama, 'slug' => $slug]) > 0) {
            Flasher::setFlash("Kategori berhasil dibuat!");
            return $this->redirect("/admin/kategori");
        }

        Flasher::setFlash("Gagal membuat kategori", "error", "Gagal!");
        return $this->redirect("/admin/kategori");
    }

    public function edit($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "Kategori tidak ditemukan",
                "error",
                "Gagal Mengedit!"
            );

            return $this->redirect("/admin/kategori");
        }

        $kategoriModel = $this->model("Kategori");
        $data["kategori"] = $kategoriModel->find($id);

        if (!$data["kategori"]) {
            Flasher::setFlash(
                "Kategori tidak ditemukan",
                "error",
                "Gagal Mengedit!"
            );

            return $this->redirect("/admin/kategori");
        }

        $data["title"] = "Edit Kategori";
        $this->view("admin/kategori/edit", $data, "admin");
    }


    public function update($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "Kategori tidak ditemukan",
                "error",
                "Gagal Mengedit!"
            );

            return $this->redirect("/admin/kategori");
        }

        $errors = Validator::validate($_POST, [
            "nama" => "required"
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            return $this->redirect("/admin/kategori/edit/$id");
        }

        $kategoriModel = $this->model("Kategori");

        $nama = trim($_POST['nama']);
        $slug = str_replace(' ', '-', strtolower($nama));

        // Cek apakah nama kategori sudah digunakan kategori lain
        $kategori = $kategoriModel
            ->withTrashed()
            ->where('nama', $nama)
            ->where('id', "!=", $id)
            ->get();

        if (!empty($kategori)) {
            $_SESSION['errors'] = [
                'nama' => 'Nama kategori sudah ada.'
            ];

            $_SESSION['old'] = $_POST;

            return $this->redirect("/admin/kategori/edit/$id");
        }

        // Pastikan hanya data yang diperlukan yang di-update
        $data = [
            'nama' => $nama,
            'slug' => $slug
        ];

        if ($kategoriModel->update($data, $id) > 0) {
            Flasher::setFlash(
                "Kategori berhasil diubah!"
            );

            return $this->redirect("/admin/kategori");
        }

        Flasher::setFlash(
            "Gagal mengubah kategori",
            "error",
            "Gagal!"
        );

        return $this->redirect("/admin/kategori/edit/$id");
    }

    public function destroy($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "Kategori tidak ditemukan",
                "error",
                "Gagal Menghapus!"
            );

            return $this->redirect("/admin/kategori");
        }

        $kategoriModel = $this->model("Kategori");
        $kategori = $kategoriModel->find($id);

        // Cek batas 24 jam
        if (!Helpers::canDeleteWithin24Hours($kategori['created_at'])) {
            Flasher::setFlash(
                "Kategori sudah lebih dari 24 jam dan tidak dapat dihapus.",
                "error",
                "Gagal Menghapus!"
            );

            return $this->redirect("/admin/kategori");
        }

        // Cek apakah user pernah melakukan transaksi
        if ($kategoriModel->hasMenu($id)) {
            Flasher::setFlash(
                "Kategori tidak dapat dihapus karena sudah memiliki menu yang terikat",
                "error",
                "Gagal Menghapus!"
            );

            return $this->redirect("/admin/kategori");
        }

        // Hapus permanen
        $kategoriModel->destroy($id);

        Flasher::setFlash(
            "Kategori berhasil dihapus",
            "success",
            "Berhasil Dihapus!"
        );

        return $this->redirect("/admin/kategori");
    }


    public function nonaktifkan($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "Kategori tidak ditemukan",
                "error",
                "Gagal Menonaktifkan!"
            );

            return $this->redirect("/admin/kategori");
        }

        $kategoriModel = $this->model("Kategori");

        $result = $kategoriModel->softDelete($id);

        $dataMenu = $this->model("Kategori")->hasMenu($id);

        foreach ($dataMenu as $menu) {
            $this->model("Menu")->softDelete($menu['id']);
        }

        if ($result > 0) {
            Flasher::setFlash(
                "Kategori berhasil dinonaktifkan",
                "success",
                "Berhasil!"
            );
        } else {
            Flasher::setFlash(
                "Kategori sudah dinonaktifkan atau tidak ditemukan",
                "error",
                "Gagal!"
            );
        }

        return $this->redirect("/admin/kategori");
    }

    public function aktifkan($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "Kategori tidak ditemukan",
                "error",
                "Gagal Menonaktifkan!"
            );

            return $this->redirect("/admin/kategori");
        }

        $kategoriModel = $this->model("Kategori");

        $dataMenu = $this->model("Kategori")->hasMenu($id);
        foreach ($dataMenu as $menu) {
            $this->model("Menu")->restore($menu['id']);
            echo "Halo";
            exit;

            // FIX INI WOIII
        }

        $result = $kategoriModel->restore($id);

        if ($result > 0) {
            Flasher::setFlash(
                "Kategori berhasil diaktifkan",
                "success",
                "Berhasil!"
            );
        } else {
            Flasher::setFlash(
                "Kategori sudah diaktifkan atau tidak ditemukan",
                "error",
                "Gagal!"
            );
        }

        return $this->redirect("/admin/kategori");
    }
}
