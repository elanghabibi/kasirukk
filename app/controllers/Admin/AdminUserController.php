<?php

class AdminUserController extends Controller
{
    public function index()
    {
        $status = $_GET['status'] ?? 'aktif';
        $keyword = trim($_GET['search'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $banyakPage = 10;

        $data['title'] = 'Kelola User';

        $userModel = $this->model('User');

        // Tentukan status soft delete
        $trashedMode = $status === 'nonaktif'
            ? 'onlyTrashed'
            : 'active';

        // Tambahkan search jika ada keyword
        if ($keyword !== '') {
            $userModel->search(
                $keyword,
                ['nama', 'email', 'role']
            );
        }

        // Ambil data dengan pagination
        if ($trashedMode == "onlyTrashed") {
            $pagination = $userModel->onlyTrashed()->paginate(
                $banyakPage,
                $page
            );
        } else if ($trashedMode == "active") {
            $pagination = $userModel->paginate(
                $banyakPage,
                $page
            );
        }

        $data['dataUser'] = $pagination['data'];
        $data['pagination'] = $pagination;
        $data['status'] = $status;
        $data['keyword'] = $keyword;

        $this->view(
            'admin/user/index',
            $data,
            'admin'
        );
    }

    public function store()
    {
        $errors = Validator::validate($_POST, [
            "nama" => "required",
            "email" => "required",
            "role" => "required",
            "password" => "required"
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            return $this->redirect("/admin/user");
        }

        $userModel = $this->model("User");

        $emailExists = $userModel->exists("email", $_POST['email']);
        if ($emailExists) {
            Flasher::setFlash("Email sudah digunakan", "error", "Gagal");
            $_SESSION['old'] = $_POST;
            return $this->redirect("/admin/user");
        }

        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if ($userModel->store($_POST) > 0) {
            Flasher::setFlash("User berhasil dibuat!");
            return $this->redirect("/admin/user");
        } else {
            Flasher::setFlash("Gagal membuat user baru", "error", "Gagal!");
            return $this->redirect("/admin/user");
        }
    }

    public function edit($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "User tidak ditemukan",
                "error",
                "Gagal Mengedit!"
            );

            return $this->redirect("/admin/user");
        }

        $userModel = $this->model("User");
        $data["user"] = $userModel->find($id);

        if (!$data["user"]) {
            Flasher::setFlash(
                "User tidak ditemukan",
                "error",
                "Gagal Mengedit!"
            );

            return $this->redirect("/admin/user");
        }

        $data["title"] = "Edit User";
        $this->view("admin/user/edit", $data, "admin");
    }

    public function update($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "User tidak ditemukan",
                "error",
                "Gagal Mengedit!"
            );

            return $this->redirect("/admin/user");
        }

        $errors = Validator::validate($_POST, [
            "nama" => "required",
            "email" => "required",
            "role" => "required"
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            return $this->redirect("/admin/user/edit/$id");
        }

        $userModel = $this->model("User");

        $emailExists = $userModel
            ->where("email", $_POST['email'])
            ->where('id', "!=", $id)
            ->get();
            
        if ($emailExists) {
            $_SESSION['errors'] = [
                'email' => 'Email sudah ada.'
            ];

            $_SESSION['old'] = $_POST;
            return $this->redirect("/admin/user/edit/{$id}");
        }

        if (!empty($_POST['password'])) {
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {
            unset($_POST['password']);
        }

        if ($userModel->update($_POST, $id) > 0) {
            Flasher::setFlash("User berhasil diubah!");
            return $this->redirect("/admin/user");
        } else {
            Flasher::setFlash("Gagal mengubah user", "error", "Gagal!");
            return $this->redirect("/admin/user/edit/$id");
        }
    }

    public function destroy($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "User tidak ditemukan",
                "error",
                "Gagal Menghapus!"
            );

            return $this->redirect("/admin/user");
        }

        $userModel = $this->model("User");
        $user = $userModel->find($id);

        // Cek batas 24 jam
        if (!Helpers::canDeleteWithin24Hours($user['created_at'])) {
            Flasher::setFlash(
                "User sudah lebih dari 24 jam dan tidak dapat dihapus.",
                "error",
                "Gagal Menghapus!"
            );

            return $this->redirect("/admin/user");
        }

        // Cek apakah user pernah melakukan transaksi
        if ($userModel->hasTransactions($id)) {
            Flasher::setFlash(
                "User tidak dapat dihapus karena sudah memiliki riwayat transaksi",
                "error",
                "Gagal Menghapus!"
            );

            return $this->redirect("/admin/user");
        }

        // Hapus permanen
        $userModel->destroy($id);

        Flasher::setFlash(
            "User berhasil dihapus",
            "success",
            "Berhasil Dihapus!"
        );

        return $this->redirect("/admin/user");
    }

    public function nonaktifkan($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "User tidak ditemukan",
                "error",
                "Gagal Menonaktifkan!"
            );

            return $this->redirect("/admin/user");
        }

        $userModel = $this->model("User");

        $result = $userModel->softDelete($id);

        if ($result > 0) {
            Flasher::setFlash(
                "User berhasil dinonaktifkan",
                "success",
                "Berhasil!"
            );
        } else {
            Flasher::setFlash(
                "User sudah dinonaktifkan atau tidak ditemukan",
                "error",
                "Gagal!"
            );
        }

        return $this->redirect("/admin/user");
    }

    public function aktifkan($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "User tidak ditemukan",
                "error",
                "Gagal Mengaktifkan!"
            );

            return $this->redirect("/admin/user");
        }

        $userModel = $this->model("User");

        $result = $userModel->restore($id);

        if ($result > 0) {
            Flasher::setFlash(
                "User berhasil diaktifkan",
                "success",
                "Berhasil!"
            );
        } else {
            Flasher::setFlash(
                "User sudah diaktifkan atau tidak ditemukan",
                "error",
                "Gagal!"
            );
        }

        return $this->redirect("/admin/user");
    }
}
