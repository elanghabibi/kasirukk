<?php

class AdminPengaturanController extends Controller
{
    public function index() {
        $data['title'] = "Pengaturan";

        $this->view("/admin/pengaturan/index", $data, "admin");
    }

    public function update()
    {
        $pengaturanModel = $this->model('Pengaturan');

        $pengaturanLama = $pengaturanModel->find(1);

        // Validasi
        $errors = Validator::validate($_POST, [
            "nama_restoran" => "required",
            "deskripsi" => "required",
            "email" => "required",
            "no_telepon" => "required",
            "alamat" => "required",
            "pesan_struk" => "required",
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            return $this->redirect('/admin/pengaturan');
        }

        $_POST['logo'] = $pengaturanLama['logo'];
        // var_dump($pengaturanLama);
        // exit;

        if (
            isset($_FILES['logo']) &&
            $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE
        ) {
            $imagePath = Upload::image(
                'logo',
                'uploads/logo'
            );

            if ($imagePath) {

                // Hapus logo lama
                if (!empty($pengaturanLama['logo']) && $pengaturanLama['logo'] !== 'uploads/logo/default.png') {
                    $oldFile = dirname(__DIR__, 3) . '/public/' . $pengaturanLama['logo'];

                    // var_dump($oldFile);
                    // exit;
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                $_POST['logo'] = $imagePath;
            }
        }

        // Update data
        if ($pengaturanModel->update($_POST, 1) > 0) {

            Flasher::setFlash(
                "Pengaturan berhasil diperbarui!"
            );
        } else {

            // var_dump($_POST);
            // exit;
            Flasher::setFlash(
                "Gagal memperbarui pengaturan",
                "error",
                "Gagal!"
            );
        }

        return $this->redirect('/admin/pengaturan');
    }
}
