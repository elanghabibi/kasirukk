<?php
class ProfilController extends Controller
{
    public function index()
    {
        $data['title'] = "Profil";

        if (Auth::user()['role'] == "kasir") {
            $redirect = str_replace("_", "/", $_GET['redirect'] ?? Auth::user()['role']);
            $data['back'] = "/" . $redirect;
        }

        $data['user'] = Auth::user();

        $this->view("profil", $data, Auth::user()['role']);
    }

    public function update()
    {
        $userModel = $this->model('User');

        
        $userLama = $userModel->find(Auth::user()['id']);

        $emailExists = $userModel->withTrashed()->exists("email", $_POST['email']);
        if ($emailExists && Auth::user()['email'] != $_POST['email']) {
            Flasher::setFlash("Email sudah digunakan", "error", "Gagal");

            $_SESSION['old'] = $_POST;
            return $this->redirect("/profil");
        }
        
        // Validasi
        $errors = Validator::validate($_POST, [
            "nama" => "required",
            "email" => "required",
            "role" => "required"
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            return $this->redirect('/profil');
        }

        $_POST['foto_profil'] = $userLama['foto_profil'];

        if (
            isset($_FILES['foto_profil']) &&
            $_FILES['foto_profil']['error'] !== UPLOAD_ERR_NO_FILE
        ) {
            $imagePath = Upload::image(
                'foto_profil',
                'uploads/foto_profil'
            );

            if ($imagePath) {

                // Hapus foto_profil lama
                if (!empty($userLama['foto_profil']) && $userLama['foto_profil'] !== 'uploads/foto_profil/default.png') {
                    $oldFile = dirname(__DIR__, 3) . '/public/' . $userLama['foto_profil'];

                    // var_dump($oldFile);
                    // exit;
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                $_POST['foto_profil'] = $imagePath;
            }
        }

        // Update data
        if ($userModel->update($_POST, Auth::user()['id']) > 0) {

            Flasher::setFlash(
                "User berhasil diperbarui!"
            );
        } else {

            // var_dump($_POST);
            // exit;
            Flasher::setFlash(
                "Gagal memperbarui user",
                "error",
                "Gagal!"
            );
        }

        return $this->redirect('/profil');
    }
}
